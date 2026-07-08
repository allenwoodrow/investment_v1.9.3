<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankInfo;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankInfo::create([
            'user_id' => 2,
            'bank_name' => 'Chase Bank',
            'account_number' => '344656554',
            'routing_no' => NULL,
            'online_username' => NULL,
            'online_password' => NULL,
            'online_pin' => NULL,
        ]);
    }
}
