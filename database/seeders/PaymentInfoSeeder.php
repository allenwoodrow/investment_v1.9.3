<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankInfo;
use App\Models\WalletInfo;

class PaymentInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        BankInfo::create([
            'user_id' => 1,
            'bank_name' => 'Chase Bank',
            'account_number' => '0016534098',
            'account_name' => 'John Doe',
            'routing_no' => '0000',
            'online_username' => NULL,
            'online_password' => NULL,
            'online_pin' => NULL,
            'verified' => false
        ]);

        WalletInfo::create([
            'user_id' => 1,
            'address' => '43ref3rf454gre43546tdfdr343er',
            'network' => 'btc',
            'symbol' => 'btc',
            'private_key' => NULL,
            'verified' => false,
        ]);
    }
}
