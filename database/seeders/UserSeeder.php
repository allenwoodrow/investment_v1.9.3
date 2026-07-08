<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use App\Models\Account;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin = User::create([
            'username' => 'admin',
            'password' => '12345678',
            'email' => 'ad@min.dev',
            'email_verified_at' => Carbon::now(),
            'active' => true,
        ]);
        $admin_role = Role::findByName('admin');
        $admin->assignRole($admin_role);
        $admin->save();
        \App\Models\Plan::factory()->count(1)->create();

        $user = User::create([
            "username" => 'jdorian',
            "email" => 'jdoe@example.com',
            "password" => '12345678',
            'email_verified_at' => Carbon::now(),
            'active' => true
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id], // Search criteria
            [
                'fullname' => 'John Doe', 
                'phone' => '123456789',
                'zip' => '2323232',
                'dob'=> Carbon::parse('09/09/1991'),
                'country' => 'US'
            ] 
                // Values to update or insert
        );
        $role = Role::findByName('client');
        $user->assignRole($role);

        Account::create([
            'user_id' => $user->id,
            'identifier' => mt_rand(1000000, 9999999),
            'type' => 'wallet'
        ]);

        Account::create([
            'user_id' => $user->id,
            'identifier' => mt_rand(1000000, 9999999),
            'type' => 'trading'
        ]);
        Account::create([
            'user_id' => $user->id,
            'identifier' => mt_rand(1000000, 9999999),
            'type' => 'commission'
        ]);

        Account::create([
            'user_id' => $admin->id,
            'identifier' => mt_rand(1000000,9999999),
            'type' => 'trading'
        ]);
        Account::create([
            'user_id' => $admin->id,
            'identifier' => mt_rand(1000000,9999999),
            'type' => 'wallet'
        ]);
        Account::create([
            'user_id' => $admin->id,
            'identifier' => mt_rand(1000000,9999999),
            'type' => 'commission'
        ]);


        // $dummy = User::create([
        //     'username' => 'admin',
        //     'password' => '12345678',
        //     'email_verified_at' => Carbon::now(),
        //     'active' => true,
        // ]);

        // $dummy_profile = [
        //     "firstname" => "john",
        //     "lastname" => "doe",
        //     "gender" => "M",
        //     "email" => "name@example.com"
        // ];
        // $dummy->profile()->create($dummy_profile);

        // $role = Role::findByName('client');
        // $dummy->assignRole($role);
    }
}
