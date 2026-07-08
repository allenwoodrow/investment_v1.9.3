<?php

namespace App\Services;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Services\JWTService;
use App\Models\User;
use App\Models\Account;
use App\Enums\TransactionType;

class TransactionService {

    public string $error;

function debitAccount(Account $account, $amount, Int $sub_id = NULL, string $description, TransactionType|string $type) {
        
        $data = [
            "payment_id" => self::id(),
            "user_id" => $account->user_id,
            "account_id" => $account->id,
            "price_amount" => $amount,
            "pay_amount" => $amount,
            "price_currency" => "USD",
            "pay_currency" => 'USD',
            "order_description" => $description,
            'payment_status' => 'finished',
            'expiration_estimate_date' => NULL,
            'sub_id' => $sub_id,
            'network' => 'LOCAL'
        ];
        $user = User::find($account->user_id)->first();
        return $this->logTransaction($user, $data, true, $type);
    }

    function creditServiceAccount($amount, $account, $sub_id = NULL, string $description, TransactionType|string $type) {
        $admin = User::where('id', 1)->first();
        
        //Use Role check here before crediting account. i just ignored it for now cuz roles are still being scoped
        if($admin != NULL) {
            $account = \App\Models\Account::where('user_id', $admin->id)->where('type', $account)->first();
            $data = [
                "payment_id" => self::id(),
                "user_id" => $admin->id,
                "account_id" => $account->id,
                "price_amount" => $amount,
                "pay_amount" => $amount,
                "price_currency" => "USD",
                "pay_currency" => 'USD',
                "order_description" => $description,
                'payment_status' => 'finished',
                'expiration_estimate_date' => NULL,
                'sub_id' => $sub_id,
                'network' => 'LOCAL'
            ];
        }
        $this->logTransaction($admin, $data, false, $type);
    }
    
    function logTransaction(User $user, array $data, bool $isDebit, TransactionType|string $type) {
        $actualType = $type instanceof TransactionType ? $type->value : $type;
        $is_sub = $this->is_sub($actualType);
        
        $transaction = Transaction::create([
            'payment_id' => $data['payment_id'],
            'user_id' => $user->id,
            'account_id' => $data['account_id'],
            'price_amount' => $data['price_amount'],
            'pay_amount' => $data['pay_amount'],
            'price_currency' => $data['price_currency'],
            'pay_currency' => $data['pay_currency'],
            'order_id' => $data['order_id'] ?? NULL,
            'order_description' => $data['order_description'],
            'sub_id' => $is_sub ? $data['sub_id'] : NULL,
            'is_debit' => $isDebit,
            'type' => $actualType,
            'payment_status' => $data['payment_status'],
            'pay_address' => $data['pay_address'] ?? NULL,
            'network' => $data['network'],
            'expiration_estimate_date' => Carbon::parse($data['expiration_estimate_date'])
        ]);

        if($transaction->id != NULL) {
            return $transaction->id;
        }
        $this->error = 'Unable to create transaction';
        return NULL;
    }

    static function id(): string {
        // This here is pure madness, but I like it.
        $len = 10;
        $number = mt_rand(11111111111, 99999999999);
        return $number;
    }

    function pending_deposits_data() {
        
        return $deposits;
    }

    function pending_deposits() {
        $all = Transaction::where('type', "=", TransactionType::WALLET_DEPOSIT->value)->get()->count();
        return $all;
    }

    function pending_bank_withdraws() {
        $all = \App\Models\WithdrawRequest::where('type', 'bank')
        ->where('status', 'pending')
        ->whereHas('bank_info')->get()->count();
        return $all;
    }

    function pending_wallet_withdraws() {
        $all = \App\Models\WithdrawRequest::where('type', 'wallet')
        ->where('status', 'pending')
        ->whereHas('wallet_info')->get()->count();
        return $all;
    }

    private function is_sub(string $type): bool {
        $ttype = TransactionType::fromString($type);
        return $ttype === TransactionType::SUBSCRIPTION;
    }

    public static function recent(int $user_id) {
        $all = Transaction::where('user_id', "=", $user_id)
        ->orderBy('created_at', 'DESC')
        ->take(20)
        ->get();
        return $all;
    }
}