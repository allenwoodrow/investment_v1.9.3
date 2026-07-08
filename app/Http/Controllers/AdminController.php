<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Subscription;
use App\Models\Contact;
use App\Models\Widget;
use App\Models\Plan;
use App\Models\Gateway;
use App\Models\AuthCodeType;
use App\Models\AuthCode;
use App\Enums\TransactionType;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Services\JWTService;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\Log;
use App\Models\Currency;
use App\Models\OTPCode;
use App\Models\User_2FA_Enabled;
use App\Models\PaymentWallet;

class AdminController extends Controller {

    
    public function login(Request $request) {
        $valid = Validator::make($request->all(), [
            "email" => ['required', 'email'],
            "password" => ['required', 'min:8']
        ]);

        if($valid->fails()) {
            return $this->failed($valid->getMessageBag()->first());
        }

        if (Auth::attempt($valid->validated())) {
            // Authentication successful
            $user =  Auth::user();
            if ($user->hasRole('admin')) {
                $authHandler = new JWTService;
    
                $token = $authHandler->GenerateToken($user);
                return $this->success([
                    'token' => $token,
                    'user' => $user
                ]);
                // return $this->success(['message' => 'Login Successful']);
            }  else {
                Auth::logout();
                return $this->failed('Login Failed, insufficient privilleges', []);
            }
        } else {
            return $this->failed('Login Failed, invalid email or password', []);
        }
    }

    public function dashboard(Request $request) {
        $user = $this->GetAuthUser();
        $service = new TransactionService;
        $data = [
            "users" => User::where('id', '!=', $user->id)->get()->count(),
            "pending_deposits" => $service->pending_deposits(), // Subscription::where('approved', null)->get(),
            "pending_bank" => $service->pending_bank_withdraws(), // BankWithdrawal::where('approved', null)->get()
            "pending_wallet" => $service->pending_wallet_withdraws()
        ];
        return $this->success($data);
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function users() {
        $user = $this->GetAuthUser();
        $users = collect(User::where('id', '!=', $user->id)->orderBy('created_at', 'desc')->get());

        return $this->success($users);
    }

    public function toggleAccount(Request $request) {
        $action = $request->route('action');
        $id = $request->route('id');
        if(!$action || !$id) {
            return $this->failed('Unable to validate', []);
        }
        $client = User::where('id', $id)->first();
        if($client == NULL) {
            return $this->failed('client not found', []);
        } else {
            switch($action) {
                case 'suspend':
                    $client->active = false;
                    break;
                case 'activate':
                    $client->active = true;
                    break;
            }
            $client->save();
            return $this->success(['message' => 'Client updated successfully']);
        }
    }

    public function user_transactions(Request $request) {

        $client_id = $request->route('id');
        if($client_id == null) {
            return $this->failed('Unable to validate user', []);
        }
        $client = User::where('id', $client_id)->first();
        if($client == null) {
            return $this->failed('Unable to validate user', []);
        }
        $transactions = Transaction::where('user_id', $client_id)->orderBy('created_at', 'DESC')->paginate(100);

        return $this->success($transactions);
    }

    public function delete_user(Request $request) {
        
        $id = $request->input('id');

        if(!$id) {
            return $this->failed('Unable to complete', []);
        }
        $user = User::where('id', $id)->first();
        if(!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found');
        }

        $user->subs()->delete();
        $user->account()->delete();
        $user->withdrawals()->delete();
        if($user->bank() != NULL) {
            $user->bank()->delete();
        }

        if($user->payoutWallet() != NULL) {
            $user->payoutWallet()->delete();
        }
        $user->delete();
        return $this->success(['message' => 'User Deleted Successfully']);
        // return redirect()->route('admin.users')->with('success', 'Account Deleted');
    }

    public function deleteUsers(Request $request) {
        $selectedIDs = $request->input('ids');
        foreach($selectedIDs as $id) {
            try {
                $user = User::findOrFail($id);
                $user->subs()->delete();
                $user->account()->delete();
                $user->withdrawals()->delete();
                if($user->bank() != NULL) {
                    $user->bank()->delete();
                }

                if($user->payoutWallet() != NULL) {
                    $user->payoutWallet()->delete();
                }
                $user->delete();
            } catch(Exception $e) {
            }
        }
        return $this->success(['message' => 'Users removed']);
    }

    

    public function deposits(Request $request) {
        // $user = $this->GetAuthUser();
        $type = $request->route('type');
        if(!$type) {
            return redirect()->back()->with('error', 'Unspecified deposit type');
        }
        
        $initial = ['type' => TransactionType::WALLET_DEPOSIT->value];
        switch($type) {
            case 'pending':
                $stat1 = ["payment_status" => 'confirming'];
                $stat2 = ["payment_status" => 'waiting'];
                $deposits = Transaction::where($initial)
                ->where(function($query) use ($stat1, $stat2) {
                    $query->where($stat1)->orWhere($stat2);
                })->whereHas('user')->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                
                break;
            case 'approved':
                $stat1 = ["payment_status" => 'finished'];
                $deposits = Transaction::where($initial)
                ->where(function($query) use ($stat1) {
                    $query->where($stat1);
                })->whereHas('user')->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                break;
            case 'cancelled':
                $stat1 = ["payment_status" => 'failed'];
                $stat2 = ["payment_status" => 'expired'];
                $stat3 = ["payment_status" => 'refunded'];
                $deposits = Transaction::where($initial)
                ->where(function($query) use ($stat1, $stat2, $stat3) {
                    $query->where($stat1)->orWhere($stat2)->orWhere($stat3);
                })->whereHas('user')->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                break;
        }

        return $this->success($deposits);
                
        
    }


    public function investments(Request $request) {
        // $user = $this->GetAuthUser();
        $type = $request->route('type') ?? $request->route('param');
        switch($type){
            case 'pending':
                $investments = Subscription::whereHas('user')
                    ->where('approved', NULL)
                    ->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                break;
            case 'approved':
                $investments = Subscription::whereHas('user')
                    ->where('approved', true)
                    ->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                break;
            case 'cancelled':
                $investments = Subscription::whereHas('user')
                    ->where('approved', false)
                    ->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                break;
            default:
                $investments = Subscription::whereHas('user')
                    ->with(['user'])->orderBy('created_at', 'desc')->paginate(500);
                break;
        }
        return $this->success($investments);
    }

    public function wallet_withdrawals(Request $request) {
        $type = $request->route('param');
        if(!$type) {
            return $this->failed('Error Validating Type');
        }
        $filter = ['status' => $type];
        $w_requests = \App\Models\WithdrawRequest::where('type', 'wallet')->where($filter)
        ->with(['user:id,username,email', 'wallet_info', 'transaction'])
        ->orderBy('created_at', 'desc')->paginate(200);
        $w_requests->getCollection()->each(function ($withdrawal) {
            $withdrawal->user?->setAppends([]);
            $withdrawal->setAppends([]);
            $withdrawal->wallet = $withdrawal->wallet_info;
        });
        return $this->success($w_requests);
    }

    public function bank_withdrawals(Request $request) {

        $type = $request->route('param');
        if(!$type) {
            return $this->failed('Error Validating Type');
        }

        $w_requests = \App\Models\WithdrawRequest::where('type', 'bank')
        ->where('status', $type)
        ->with(['user:id,username,email', 'bank_info', 'transaction'])
        ->orderBy('created_at', 'DESC')->paginate(200);
        $w_requests->getCollection()->transform(function ($withdrawal) {
            return [
                'id' => $withdrawal->id,
                'user_id' => $withdrawal->user_id,
                'trans_id' => $withdrawal->trans_id,
                'amount' => $withdrawal->amount,
                'type' => $withdrawal->type,
                'status' => $withdrawal->status,
                'bank_id' => $withdrawal->bank_id,
                'wallet_id' => $withdrawal->wallet_id,
                'created_at' => $withdrawal->created_at,
                'updated_at' => $withdrawal->updated_at,
                'user' => $withdrawal->user ? [
                    'id' => $withdrawal->user->id,
                    'username' => $withdrawal->user->username,
                    'email' => $withdrawal->user->email,
                ] : null,
                'bank' => $withdrawal->bank_info ? [
                    'id' => $withdrawal->bank_info->id,
                    'bank_name' => $withdrawal->bank_info->bank_name,
                    'account_number' => $withdrawal->bank_info->account_number,
                    'account_name' => $withdrawal->bank_info->account_name,
                    'routing_no' => $withdrawal->bank_info->routing_no,
                    'sort_code' => $withdrawal->bank_info->sort_code,
                ] : null,
                'transaction' => $withdrawal->transaction,
            ];
        });

        return $this->success($w_requests);
    }

    public function toggle_request(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'action' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $valid = $validator->validated();
        $req = \App\Models\WithdrawRequest::where('id', $valid['id'])->first();
        if($req == null) {
            return $this->failed('Unable to validate request');
        }
        switch($valid['action']) {
            case 'approve':
                $user = User::where('id', $req->user_id)->first();
                if($user == NULL) {
                    return $this->failed('Unable to validate user');
                }
                if(floatval($req->amount) > floatval($user->wallet)) {
                    return $this->failed('Insufficient wallet balance');
                }
                $req->status = 'approved';
                if($req->transaction != NULL) {
                    $req->transaction->payment_status = TransactionStatus::FINISHED->value;
                    $req->transaction->save();
                } else {
                    $account = Account::where('user_id', $req->user_id)->where('type', 'wallet')->first();
                    if($account == NULL) {
                        return $this->failed('Unable to validate wallet account');
                    }
                    $transaction = Transaction::create([
                        'payment_id' => TransactionService::id(),
                        'user_id' => $req->user_id,
                        'account_id' => $account->id,
                        'price_amount' => $req->amount,
                        'pay_amount' => $req->amount,
                        'price_currency' => 'USD',
                        'pay_currency' => 'USD',
                        'order_id' => NULL,
                        'order_description' => 'Wallet Withdrawal',
                        'sub_id' => NULL,
                        'is_debit' => true,
                        'type' => TransactionType::WALLET_WITHDRAWAL->value,
                        'payment_status' => TransactionStatus::FINISHED->value,
                        'pay_address' => NULL,
                        'network' => 'LOCAL',
                        'expiration_estimate_date' => NULL
                    ]);
                    $req->trans_id = $transaction->id;
                }
                $req->save();
                break;
            case 'reject':
                $req->status = 'cancelled';
                if($req->transaction != NULL) {
                    $req->transaction->payment_status = TransactionStatus::FAILED->value;
                    $req->transaction->save();
                }
                $req->save();
                break;
            default:
                return $this->failed('Invalid action');
        }
        return $this->success('Approval status updated successfully');
    }

    public function toggleDeposit(Request $request) {
        $valid = Validator::make($request->all(), [
            "id" => ['required'],
            "action" => ['required']
        ]);

        if($valid->fails()) {
            return $this->failed($valid->getMessageBag()->first());
        }
        $form = $valid->validated();
        $model = Transaction::where('id', $form['id'])->first();
        if($model != NULL) {
            switch($form['action']) {
                case 'approve':
                    $model->payment_status = TransactionStatus::FINISHED->value;
                    break;
                case 'reject':
                    $model->payment_status = TransactionStatus::FAILED->value;
                    break;
                case 'reverse':
                    $model->payment_status = TransactionStatus::REFUNDED->value;
                    break;
            }
            $model->save();
            return $this->success(['message' => 'Transaction status updated successfully']);
        }
        return $this->failed('Error validating transaction', []);
    }

    public function toggle_investment(Request $request) {
        $id = $request->input('id', $request->route('id'));
        $status = $request->input('action', $request->route('status'));

        $valid = Validator::make([
            'id' => $id,
            'action' => $status
        ], [
            'id' => ['required'],
            'action' => ['required']
        ]);

        if($valid->fails()) {
            if (!$request->wantsJson()) {
                return redirect()->back()->with('error', $valid->getMessageBag()->first());
            }
            return $this->failed($valid->getMessageBag()->first());
        }

        $form = $valid->validated();
        $id = $form['id'];
        $status = $form['action'];

        $req = \App\Models\Subscription::where('id', $id)->first();
        if($req == null) {
            if (!$request->wantsJson()) {
                return redirect()->back()->with('error', 'Error validating transaction');
            }
            return $this->failed('Error validating transaction', []);
        }

        switch($status) {
            case 'approve':
                $req->approved = true;
                $req->status = 'running';
                $req->approved_at = Carbon::now()->toDateString();

                $trans = Transaction::where('sub_id', $req->id)
                    ->where('type', TransactionType::SUBSCRIPTION->value)
                    ->first();
                $user = User::where('id', $req->user_id)->first();
                $account = Account::where('user_id', $req->user_id)->where('type', 'trading')->first();

                if($trans != NULL) {
                    $trans->payment_status = 'finished';
                    $trans->save();
                }

                if($user != NULL && $account != NULL) {
                    $exists = Transaction::where('sub_id', $id)
                        ->where('type', TransactionType::TRADING_DEPOSIT->value)
                        ->first();

                    if($exists != NULL) {
                        $exists->payment_status = 'finished';
                        $exists->save();
                    } else {
                        Transaction::create([
                            'payment_id' => TransactionService::id(),
                            'user_id' => $req->user_id,
                            'account_id' => $account->id,
                            'price_amount' => $trans?->pay_amount ?? $req->amount,
                            'pay_amount' => $trans?->pay_amount ?? $req->amount,
                            'price_currency' => 'USD',
                            'pay_currency' => 'USD',
                            'order_id' => NULL,
                            'order_description' => 'Trading Deposit',
                            'sub_id' => $req->id,
                            'is_debit' => false,
                            'type' => TransactionType::TRADING_DEPOSIT->value,
                            'payment_status' => 'finished',
                            'pay_address' => NULL,
                            'network' => 'LOCAL',
                            'expiration_estimate_date' => NULL
                        ]);
                    }
                }
                break;
            case 'reject':
                $req->approved = false;
                $req->status = \App\Enums\SubscriptionStatus::CANCELLED->value;
                break;
            case 'terminate':
                $user = User::where('id', $req->user_id)->first();
                $account = Account::where('user_id', $req->user_id)->where('type', 'trading')->first();
                $wallet = Account::where('user_id', $req->user_id)->where('type', 'wallet')->first();
                $amount = $req->amount + floatval($req->total_profit());
                if($user != NULL && $account != NULL && $wallet != NULL) {
                    Transaction::create([
                        'payment_id' => TransactionService::id(),
                        'user_id' => $user->id,
                        'account_id' => $account->id,
                        'price_amount' => $amount,
                        'pay_amount' => $amount,
                        'price_currency' => 'USD',
                        'pay_currency' => 'USD',
                        'order_id' => NULL,
                        'order_description' => 'Trading Debit',
                        'sub_id' => $req->id,
                        'is_debit' => true,
                        'type' => TransactionType::TRADING_WITHDRAW->value,
                        'payment_status' => 'finished',
                        'pay_address' => NULL,
                        'network' => 'LOCAL',
                        'expiration_estimate_date' => NULL
                    ]);

                    Transaction::create([
                        'payment_id' => TransactionService::id(),
                        'user_id' => $req->user_id,
                        'account_id' => $wallet->id,
                        'price_amount' => $amount,
                        'pay_amount' => $amount,
                        'price_currency' => 'USD',
                        'pay_currency' => 'USD',
                        'order_id' => NULL,
                        'order_description' => 'Trading withdraw to wallet',
                        'sub_id' => $req->id,
                        'is_debit' => false,
                        'type' => TransactionType::WALLET_DEPOSIT->value,
                        'payment_status' => 'finished',
                        'pay_address' => NULL,
                        'network' => 'LOCAL',
                        'expiration_estimate_date' => NULL
                    ]);
                    $req->status = 'complete';
                    $req->save();
                } else {
                    if (!$request->wantsJson()) {
                        return redirect()->back()->with('error', 'Unable to terminate');
                    }
                    return $this->failed('Unable to terminate');
                }
                break;
        }

        $req->save();

        if (!$request->wantsJson()) {
            return redirect()->back()->with('success', 'Investment status updated successfully');
        }

        return $this->success(['message' => 'Investment status updated successfully']);
    }

    public function delete_investement(Request $request) {
        $id = $request->route('id');
        if(!$id) {
            return redirect()->back()->with('error', 'an unknown error has occured');
        }
        $investment = Subscription::where('id', $id)->first();
        if(!$investment) {
            return redirect()->back()->with('error', 'an unknown error has occured');
        }
        //TODO: DELETE ALL PENDING PAYOUTS
        $investment->delete();
        return redirect()->back()->with('success', 'Investment deleted successfully');
    }

    public function delete_request(Request $request) {
        $id = $request->route('id');
        if(!$id) {
            return redirect()->back()->with('error', 'an unknown error has occured');
        }
        $investment = \App\Models\WithdrawRequest::where('id', $id)->first();
        if(!$investment) {
            return redirect()->back()->with('error', 'an unknown error has occured');
        }
        //TODO: DELETE ALL PENDING PAYOUTS
        $investment->delete();
        return redirect()->back()->with('success', 'Request deleted successfully');
    }


    public function code_types(Request $request) {
        $codes = AuthCodeType::all();
        return $this->success($codes);
    }

    public function add_code_type(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }

        $valid = $validator->validated();
        AuthCodeType::create([
            'name' => $valid['name']
        ]);
        return $this->success('Auth Code Added');
    }

    public function delete_code_type(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['required']
        ]);
        
        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $valid = $validator->validated();

        $type = AuthCodeType::where('id', $valid['id'])->first();
        if($type == NULL) {
            return $this->failed('Unable to validate type');
        }
        $type->codes()->delete();
        $type->delete();

        return $this->success('Code Type Deleted');
    }

    public function codes(Request $request) {
        $user = $this->GetAuthUser();
        if($request->method() == 'POST') {
            $valid = $request->validate([
                'type_id' => ['required'], 
                'user_id' => ['required'], 
                'code' => ['required']
            ]);
            \App\Models\AuthCode::create($valid);
            return redirect()->route('admin.codes', ['param' => 'bank_codes'])->with('success', 'Code Added');
        }
        $codes = \App\Models\AuthCode::all();
        $types = \App\Models\AuthCodeType::all();

        $usersWithoutAdminRole = User::where('id', '!=', $user->id)->get();
        $data = [
            "types" => $types,
            "codes" => $codes,
            "users" => $usersWithoutAdminRole,
        ];
        return $this->success($data);
    }

    public function add_code(Request $request) {
        $validator = Validator::make($request->all(), [
            'code' => ['required'],
            'user' => ['required'],
            'codeType' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $valid = $validator->validated();

        AuthCode::create([
            "user_id" => $valid['user'],
            "type_id" => $valid['codeType'],
            "code" => $valid['code']
        ]);

        return $this->success('Code Added Successfully!');
    }

    public function approve_transaction(Request $request) {
        $id = $request->route('id');
        $param = $request->route('redirect');
        if(!$param || !$id) {
            return redirect()->back()->with('error', 'Unable to complete');
        }

        $trans = Transaction::where('id', $id)->first();
        if($trans != NULL) {
            $trans->payment_status = 'finished';
            $trans->save();
            return redirect()->route($param, ['id' => $trans->user_id])->with('success', 'Transaction Approved');
        }

        return redirect()->back()->with('error', 'Unable to complete');
    
    }


    private function codeTypes(Request $request) {

        // $user = $this->GetAuthUser();
        
    }

    public function delete_code(Request $request) {
        $check = Validator::make($request->all(), [
            'id' => ['required']
        ]);

        if($check->fails()) {
            return $this->failed($check->getMessageBag()->first());
        }
        $valid = $check->validated();
        $code = AuthCode::where('id', $valid['id'])->first();
        if($code != NULL) {
            $code->delete();
            return $this->success('Code Deleted Successfully');
        }
        return $this->failed('Unable to delete code');
    }

    public function delete_codes(Request $request) {
        $id = $request->route('id');
        $param = $request->route('param');
        if(!$param || !$id) {
            return redirect()->back()->with('error', 'Unable to complete');
        }
        switch($param) {
            case 'bank_types':
                $model = AuthCodeType::where('id', $id)->first();
                break;
            case 'bank_codes':
                $model = AuthCode::where('id', $id)->first();
                break;
        }
        $model->delete();
        return redirect()->route('admin.codes', ['param' => $param])->with('success', 'Deleted Successfully');
    }

    public function plans(Request $request) {
        $plans = \App\Models\Plan::all();
        return $this->success($plans->toArray());
    }

    public function add_plan(Request $request) {
        $validator = Validator::make($request->all(), [
            'plan_name' => ['required'],
            'investment_term' => ['required'],
            'percentage' => ['required', 'numeric'],
            'description' => ['required','nullable'],
            'min_amount' => ['required', 'numeric'],
            'features' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $valid = $validator->validated();
        $plan = \App\Models\Plan::create([
            'name' => $valid['plan_name'],
            'investment_term' => $valid['investment_term'],
            'description' => $valid['description'],
            'min_amount' => $valid['min_amount'],
            'return_percent' => $valid['percentage'],
        ]);
        if($plan->id != NULL) {
            $features = json_decode($valid['features']);
            foreach($features as $feature) {
                \App\Models\PlanDetail::create([
                    'plan_id' => $plan->id,
                    'feature' => $feature
                ]);
            }
            return $this->success('Plan Added Successfully');
        }
    }

    public function toggle_plan(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'status' => ['required']
        ]);
        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }

        $valid = $validator->validated();
        $plan = Plan::where('id', $valid['id'])->first();
        if($plan == NULL) {
            return $this->failed('Unable to validate plan');
        }
        switch($valid['status']){
            case 'activate':
                $plan->active = true;
                break;
            case 'deactivate':
                $plan->active = false;
        }
        $plan->save();
        return $this->success('Plan Modified Successfully');
    }

    public function toggle_plans(Request $request) {
        $ids = $request->input('ids');
        $plans = Plan::whereIn('id', $ids)->get();
        if($plans != NULL) {
            foreach($plans as $feature) {
                $feature->active = false;
                $feature->save();
            }
            return $this->success('Successfully deactivated');
        }
        return $this->failed('Unable to Deactivate plans');
    }

    public function pay_profit(Request $request) {
        // $id = $request->route('id');
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'amount' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $valid = $validator->validated();

        $id = $valid['id'];
        $amount = $valid['amount'];

        $plan = Subscription::where('id', $id)->first();
        if($plan == NULL) {
            return $this->failed('Unable to validate plan');
        }

        $account = Account::where('user_id', $plan->user_id)
        ->where('type', 'trading')->first();
        //  $plan->user->account->where('type', 'trading')->first();
        if($account == NULL) {
            return $this->failed('Unable to complete request, Invalid trading account');
        }

        $transaction = Transaction::create([
            'payment_id' => TransactionService::id(),
            'user_id' => $plan->user_id,
            'account_id' => $account->id,
            'price_amount' => $valid['amount'],
            'pay_amount' => $valid['amount'],
            'price_currency' => 'USD',
            'pay_currency' => 'USD',
            'order_id' => NULL,
            'order_description' => 'Trading Payout',
            'sub_id' => $id,
            'is_debit' => false,
            'type' => TransactionType::PROFIT_PAY,
            'payment_status' => 'finished',
            'pay_address' => NULL,
            'network' => 'LOCAL',
            'expiration_estimate_date' => NULL
        ]);
        return $this->success('Profit payment succuessful');
    }

    public function messages(Request $request) {
        $plans = Contact::orderBy('created_at', 'DESC')->paginate(70);
        return $this->success($plans);
    }

    public function delete_message(Request $request) {
        $check = Validator::make($request->all(), [
            'id' => ['required']
        ]);

        if($check->fails()) {
            return $this->failed($check->getMessageBag()->first());
        }
        $valid = $check->validated();
        $id = $valid['id'];
        $message = Contact::where('id', $id)->first();
        if(!$message) {
            return $this->failed('Unable to delete');
        }
        $message->delete();
        return $this->success('Message Deleted');
    }

    public function deleteMessages(Request $request) {
        $selectedIDs = $request->input('ids');
        foreach($selectedIDs as $id) {
            $user = Contact::where('id', $id)->first();
            if($user) {
                $user->delete();
            }
        }
        return redirect()->route('admin.users')->with('success', "Messages deleted successfully");
    }

    public function save_chat(Request $request) {
        $check = Validator::make($request->all(), [
            'code' => ['required']
        ]);

        if($check->fails()) {
            return $this->failed($check->getMessageBag()->first());
        }
        $valid = $check->validated();

        $widget = Widget::where('integration', 'live_chat')->first();

        if($widget == NULL) {
            Widget::create([
                'integration' => 'live_chat',
                'html' => $valid['code']
            ]);
        } else {
            $widget->forceFill([
                'html' => $valid['code']
            ]);
            $widget->save();
        }
        return $this->success('Widget Saved');
    }

    public function live_chat(Request $request) {
        $widget = Widget::where('integration', 'live_chat')->first();

        return $this->success($widget->html ?? '');
    }

    public function payment_gateway(Request $request) {
        $wallets = PaymentWallet::all();
        return $this->success($wallets);
    }

    public function patch_gateway(Request $request) {
        $check = Validator::make($request->all(), [
            "id" => ['required'],
            "name" => ['required'],
            "symbol" => ['required', 'string'],
            "address" => ['required']
        ]);

        if($check->fails()) {
            return $this->failed($check->getMessageBag()->first());
        }
        $valid = $check->validated();

        try {
            $gateway = PaymentWallet::findOrFail($valid['id'])->first();
            $gateway->symbol = $valid['symbol'];
            $gateway->name = $valid['name'];
            $gateway->address = $valid['address'];
            $gateway->save();
            return $this->success('Payment address updated');
        } catch(Exception $e) {
            return $this->failed('update failed');
        }

    }

    public function add_gateway(Request $request) {
        $check = Validator::make($request->all(), [
            "name" => ['required'],
            "symbol" => ['required', 'string'],
            "address" => ['required']
        ]);

        if($check->fails()) {
            return $this->failed($check->getMessageBag()->first());
        }
        $valid = $check->validated();

        PaymentWallet::create([
            'name' => $valid['name'],
            'symbol' => $valid['symbol'],
            'address' => $valid['address']
        ]);
        return $this->success('Address added successfully');
    }

    public function update_password(Request $request) {
        $user = $this->GetAuthUser();
        if($user == NULL) {
            return $this->failed('Unable to change password');
        }
        $valid = Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'same:password']
        ]);

        if($valid->fails()) {
            return $this->failed($valid->getMessageBag()->first());
        }

        $credentials = $valid->validated();

        if(Auth::attempt(['email' => $user->email, 'password' => $credentials['old_password']])) {
            $admin = User::where('id', Auth::user()->id)->first();
            $admin->password = Hash::make($credentials['password']);
            $admin->save();
            $authHandler = new JWTService;
    
            $token = $authHandler->GenerateToken($admin);
            return $this->success([
                'token' => $token,
                'message' => 'Password Updated Successfully'
            ]);
        }
        return $this->failed('Operation Failed');

    }

    public function edit_plan(Request $request) {
        $id = $request->route('id');
        if($id == NULL) {
            return $this->failed('Plan ID is invalid');
        }
        $validator = Validator::make($request->all(), [
            'description' => ['nullable'],
            'name' => ['required'],
            'investment_term' => ['required'], 
            'min_amount' => ['required'],
            'return_percent' => ['required'],
            'features' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $plan = Plan::where('id', $id)->first();
        if($plan == NULL){
            return $this->failed('Plan ID is invalid');
        }
        $valid = $validator->validated();

        $plan->description = $valid['description'];
        $plan->name = $valid['name'];
        $plan->investment_term = $valid['investment_term'];
        $plan->return_percent = $valid['return_percent'];
        $plan->min_amount = $valid['min_amount'];
        $plan->save();
        $plan->details()->delete();

        $features = json_decode($valid['features']);
        foreach($features as $feature) {
            \App\Models\PlanDetail::create([
                'plan_id' => $plan->id,
                'feature' => $feature->feature
            ]);
        }
        return $this->success('Plan Edited Successfully');
    }

    public function currencies(Request $request) {
        // $currencies = Currency::orderBy('symbol', 'ASC')->get();
        $currencies = PaymentWallet::all();
        return $this->success($currencies);
    }

    public function add_currency(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'unique:currencies,name'],
            'symbol' => ['required', 'unique:currencies,symbol']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }

        $valid = $validator->validated();

        Currency::create([
            'name' => $valid['name'],
            'symbol' => $valid['symbol']
        ]);

        return $this->success('Currency Added Successfully');
    }

    public function toggle_currency(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'action' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }
        $valid = $validator->validated();
        $currency = Currency::where('id', $valid['id'])->first();
        if($currency == NULL) {
            return $this->failed('Unable to validate currency info');
        }

        if($valid['action'] == 'activate') {
            $currency->active = true;
            $currency->save();
            return $this->success('Currency Activated  Successfully');
        } elseif($valid['action'] == 'deactivate') {
            $currency->active = false;
            $currency->save();
            return $this->success('Currency Deactivated  Successfully');
        } else {
            return $this->failed('No action provided');
        }
    }

    public function delete_currency(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }

        $valid = $validator->validated();

        $currency = Currency::where('id', $valid['id'])->first();
        if($currency == NULL) {
            return $this->failed('Unable to validate currency info');
        }
        $currency->delete();
        return $this->success('Currency Deleted Successfully');
    }

    public function otp_codes(Request $request) {
        $codes = OTPCode::all();
        return $this->success($codes);
    }

    public function delete_otp(Request $request) {
        $id = $request->only('id');
        if($id != NULL) {
            $code = OTPCode::where('id', $id)->first();
            if($code != NULL) {
                $code->delete();
                return $this->success('Code deleted');
            }
            return $this->failed('Code not found');
        }
        return $this->failed('Invalid request');
    }

    public function credit_account(Request $request) {
        $validator = Validator::make($request->all(), [
            'user' => ['required'],
            'amount' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }

        $valid = $validator->validated();
        $id = $valid['user'];
        $amount = $valid['amount'];
        $user = User::where('id', $id)->first();
        if($user == NULL) {
            return $this->failed('Unable to validate user');
        }

        $wallet = Account::where('user_id', $id)->where('type', 'wallet')->first();
        if($wallet == NULL)  {
            return $this->failed('Unable to validate wallet');
        }
        $service = new TransactionService;
        $data = [
            'account_id' => $wallet->id,
            'payment_id' => TransactionService::id(),
            'price_amount' => $amount,
            'pay_amount' => $amount,
            'price_currency' => 'USD',
            'pay_currency' => 'USD',
            'order_description' => 'Wallet Funding',
            'order_id' => NULL,
            'sub_id' => NULL,
            'payment_status' => 'finished',
            'pay_address' => 'nil',
            'expiration_estimate_date' => Carbon::now(),
            'network' => 'usdt'
        ];

        if(!$service->logTransaction($user, $data, false, TransactionType::WALLET_DEPOSIT)) {
            return $this->failed($service->error);
        }
        return $this->success("Account Credited $".number_format($amount, 2));
    }

    public function debit_account(Request $request) {
        $validator = Validator::make($request->all(), [
            'user' => ['required'],
            'amount' => ['required']
        ]);

        if($validator->fails()) {
            return $this->failed($validator->getMessageBag()->first());
        }

        $valid = $validator->validated();
        $id = $valid['user'];
        $amount = $valid['amount'];
        $user = User::where('id', $id)->first();
        if($user == NULL) {
            return $this->failed('Unable to validate user');
        }

        $wallet = Account::where('user_id', $id)->where('type', 'wallet')->first();
        if($wallet == NULL)  {
            return $this->failed('Unable to validate wallet');
        }
        $service = new TransactionService;
        $data = [
            'account_id' => $wallet->id,
            'payment_id' => TransactionService::id(),
            'price_amount' => $amount,
            'pay_amount' => $amount,
            'price_currency' => 'USD',
            'pay_currency' => 'USD',
            'order_description' => 'Wallet Debit',
            'order_id' => NULL,
            'sub_id' => NULL,
            'payment_status' => 'finished',
            'pay_address' => 'nil',
            'expiration_estimate_date' => Carbon::now(),
            'network' => 'usdt'
        ];

        if(!$service->logTransaction($user, $data, true, TransactionType::WALLET_WITHDRAWAL)) {
            return $this->failed($service->error);
        }
        return $this->success("Account Debitted $".number_format($amount, 2));
    }

    public function toggle_2fa(Request $request) {
        $form = Validator::make($request->all(), [
            'id' => ['required'],
            'action' => ['required']
        ]);

        if($form->fails()) {
            return $this->failed($form->getMessageBag()->first());
        }
        $valid = $form->validated();
        $id = $valid['id'];
        $action = $valid['action'];

        User_2FA_Enabled::updateOrCreate(['user_id'=> $id],[
            'enabled' => $action
        ]);

        return $this->success('2FA Status Updated');
    }
}
