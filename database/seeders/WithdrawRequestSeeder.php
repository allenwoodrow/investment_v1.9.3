<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WithdrawRequest;

class WithdrawRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Pending Wallet

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'wallet',
            'status' => 'pending',
            'wallet_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'wallet',
            'status' => 'pending',
            'wallet_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'wallet',
            'status' => 'pending',
            'wallet_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'wallet',
            'status' => 'pending',
            'wallet_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'wallet',
            'status' => 'pending',
            'wallet_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'wallet',
            'status' => 'pending',
            'wallet_id' => 1
        ]);


        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'bank',
            'status' => 'pending',
            'bank_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'bank',
            'status' => 'pending',
            'bank_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'bank',
            'status' => 'pending',
            'bank_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'bank',
            'status' => 'pending',
            'bank_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'bank',
            'status' => 'pending',
            'bank_id' => 1
        ]);

        WithdrawRequest::create([
            'user_id' => 2,
            'trans_id' => NULL,
            'amount' => rand(100,999),
            'type' => 'bank',
            'status' => 'pending',
            'bank_id' => 1
        ]);

    }
}
