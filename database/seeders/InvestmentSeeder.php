<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subscription::factory()->count(9)->create();
    }
}
