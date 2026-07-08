<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WithdrawRequest;
use App\Models\WalletInfo;
use App\Models\BankInfo;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Enums\TransactionType;
use App\Models\OTPCode;

class WithdrawalController extends Controller
{

    public function bank_withdrawal(Request $request) {
        $form = Validator::make($request->all(), [
            'beneficiary_bank' => ['required'],
            'beneficiary_account' => ['required'],// string
            'beneficiary_name' => ['required'], //: string
            'amount' => ['required'], //: number'
            'routing_no' => ['nullable'], //: string
            'sort_code' => ['nullable'], //: string
        ]);

        if($form->fails()) {
            return $this->failed($form->getMessageBag()->first());
        } else {
            $user = $this->GetAuthUser();
            if($user == NULL) {
                return $this->failed('Unable to validate account');
            }
            $valid = $form->validated();
            $account = $user->account->where('type', 'wallet')->first();

            if($account == NULL) {
                return $this->failed('Unable to validate transaction');
            }

            $bank = BankInfo::where('user_id', $user->id)->where('account_number', $valid['beneficiary_account'])->first();
            if($bank == NULL) {
                $bank = BankInfo::updateOrCreate([
                    'user_id' => $user->id,
                    'account_number' => $valid['beneficiary_account']
                ],[
                    'bank_name' => $valid['beneficiary_bank'],
                    'account_number' => $valid['beneficiary_account'],
                    'account_name' => $valid['beneficiary_name'],
                    'routing_no' => $valid['routing_no'] ?? NULL,
                    'sort_code' => $valid['sort_code'] ?? NULL,
                ]);
            }
            $response_data = [
                'amount' => $valid['amount']
            ];

            if($user->multi == false) {
                $trans = $this->logBankWithdraw($user, $account, $bank, $valid['amount']);
                $response_data['payment_id'] = $trans->id;
                $response_data['twoFactor'] = false;
            } else {
                $code = OTPCode::updateOrCreate(['user_id' => $user->id], ['code' => rand(100000, 999999)]);
                $response_data['twoFactor'] = true;
            }
            return $this->success($response_data);
        }
    }

    public function validate_withdraw_otp(Request $request) {
        $form = Validator::make($request->all(), [
            'amount'=> ['required'],
            'code' => ['required'],
            'withdraw_type' => ['required']
        ]);

        
        if($form->fails()) {
            return $this->failed($form->getMessageBag()->first());
        } else {
            $user = $this->GetAuthUser();
            $valid = $form->validated();
            if($user == NULL) {
                return $this->failed('Unable to validate account');
            }

            $code_check = OTPCode::where('user_id', $user->id)->where('code', $valid['code'])->first();

            if($code_check == NULL) {
                return $this->failed('Invalid code entered');
            }
            $account = $user->account->where('type', 'wallet')->first();
            if($account == NULL) {
                return $this->failed('Unable to validate transaction account');
            }

            $trans = NULL;
            switch($valid['withdraw_type']) {
                case 'bank':
                    $bank = $user->bank;
                    if($bank == NULL) {
                        return $this->failed('Unable to validate bank withdrawal details');
                    }
                    $trans = $this->logBankWithdraw($user, $account, $bank, $valid['amount']);
                    break;
                case 'wallet':
                    $wallet = $user->payoutWallet;
                    if($wallet == NULL) {
                        return $this->failed('Unable to validate wallet withdrawal details');
                    }
                    $trans = $this->logWalletWithdraw($user, $account, $wallet, $valid['amount']);
                    break;
                default:
                    return $this->failed('Invalid withdrawal type');
            }
            
            if($trans != NULL) {
                if($user->hasCheckpoints) {
                    $trans->require_2fa = true;
                    $trans->save();
                    return $this->success(['twoFactor' => false, 'amount' => $valid['amount'], 'checkpoint' => true, 'payment_id' => $trans->id]);
                } else {
                    return $this->success(['twoFactor' => false, 'amount' => $valid['amount'], 'payment_id' => $trans]);
                }
            } else {
                return $this->failed('Unable to validate withdrawal request, please try again later');
            }
        }
        
    }

    private function logBankWithdraw($user, $account, $bank, $amount) {
        
        $service = new TransactionService();
        $transaction = Transaction::create([
            'payment_id' => TransactionService::id(),
            'user_id' => $user->id,
            'account_id' => $account->id,
            'price_amount' => $amount,
            'pay_amount' => $amount,
            'price_currency' => 'USD',
            'pay_currency' => 'USD',
            'order_id' => NULL,
            'order_description' => 'Withdraw to bank',
            'sub_id' => NULL,
            'is_debit' => true,
            'type' => TransactionType::WALLET_WITHDRAWAL,
            'payment_status' => 'waiting',
            'pay_address' => NULL,
            'network' => 'LOCAL',
            'expiration_estimate_date' => NULL
        ]);
        if($transaction->id != NULL) {
            $req = WithdrawRequest::create([
                'user_id' => $user->id,
                'trans_id' => $transaction->id,
                'amount' => $amount,
                'type' => 'bank',
                'status' => 'pending',
                'bank_id' => $bank->id,
                'wallet_id' => NULL,
            ]);
            return $req;
        }
        return NULL;
    }

    public function wallet_withdrawal(Request $request) {
        $form = Validator::make($request->all(), [
            'address' => ['required'],
            'network' => ['required'],
            'symbol' => ['required'],
            'amount' => ['required']
        ]);
        if($form->fails()) {
            return $this->failed($form->getMessageBag()->first());
        } else {
            $user = $this->GetAuthUser();
            if($user == NULL) {
                return $this->failed('Unable to validate account');
            }
            $valid = $form->validated();
            $account = $user->account->where('type', 'wallet')->first();

            if($account == NULL) {
                return $this->failed('Unable to validate transaction');
            }

            $wallet = WalletInfo::where('user_id', $user->id)->where('address', $valid['address'])->first();
            if($wallet == NULL) {
                $wallet = WalletInfo::updateOrCreate([
                    'user_id' => $user->id,
                    'address' => $valid['address']
                ],[
                    'address' => $valid['address'],
                    'network' => $valid['network'],
                    'symbol' => $valid['symbol'],
                ]);
            }

            $response_data = [
                'amount' => $valid['amount']
            ];

            if($user->multi == false) {
                $trans = $this->logWalletWithdraw($user, $account, $wallet, $valid['amount']);
                $response_data['payment_id'] = $trans->id;
                $response_data['twoFactor'] = false;
            } else {
                $code = OTPCode::updateOrCreate(['user_id' => $user->id], ['code' => rand(100000, 999999)]);
                $response_data['twoFactor'] = true;
            }
            return $this->success($response_data);
        }
    }

    private function logWalletWithdraw($user, $account, $wallet, $amount) {
        $service = new TransactionService();
        $transaction = Transaction::create([
            'payment_id' => TransactionService::id(),
            'user_id' => $user->id,
            'account_id' => $account->id,
            'price_amount' => $amount,
            'pay_amount' => $amount,
            'price_currency' => 'USD',
            'pay_currency' => 'USD',
            'order_id' => NULL,
            'order_description' => 'Withdraw to wallet',
            'sub_id' => NULL,
            'is_debit' => true,
            'type' => TransactionType::WALLET_WITHDRAWAL,
            'payment_status' => 'waiting',
            'pay_address' => NULL,
            'network' => 'LOCAL',
            'expiration_estimate_date' => NULL
        ]);
        if($transaction->id != NULL) {
            $req = WithdrawRequest::create([
                'user_id' => $user->id,
                'trans_id' => $transaction->id,
                'amount' => $amount,
                'type' => 'wallet',
                'status' => 'pending',
                'bank_id' => NULL,
                'wallet_id' => $wallet->id,
            ]);
            return $req;
        }
        return NULL;
    }


    public function withdraw_channels(Request $request) {
        $user = $this->GetAuthUser();
        if($user == NULL) { 
            return $this->failed('unable to validate'); 
        }
        $wallet = WalletInfo::where('user_id', $user->id)->first();
        $bank = BankInfo::where('user_id', $user->id)->first();

        $data = [];
        if($wallet != NULL) {
            $data['wallet'] = [
                'address' => $wallet->address,
                'network' => $wallet->network,
                'symbol' => $wallet->symbol,
            ];
        } else {
            $data['wallet'] = NULL;
        }

        if($bank != NULL) {
            $data['bank'] = [
                'bank_name' => $bank->bank_name,
                'account_name' => $bank->account_name,
                'account_number' => $bank->account_number,
                'routing_no' => $bank->routing_no,
                'sort_code' => $bank->sort_code
            ];
        } else {
            $data['bank'] = null;
        }
        return $this->success($data);
    }

    public function withdraw_history(Request $request) {
        $user = $this->GetAuthUser();
        if($user == NULL) { 
            return $this->failed('unable to validate'); 
        }
        $history = WithdrawRequest::where('user_id', $user->id)
        ->whereHas('transaction')->with('transaction')->orderBy('created_at', 'DESC')->take(50)->get();
        return $this->success($history);
    }
}
