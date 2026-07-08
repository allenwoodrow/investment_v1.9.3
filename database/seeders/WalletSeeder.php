<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WalletInfo;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WalletInfo::create([
            'user_id' => 2,
            'address' => '34afer3r343ergt456gyt7y565edfere',
            'network' => 'btc',
            'symbol' => 'BTC',
            'private_key' => NULL
        ]);
    }
}
