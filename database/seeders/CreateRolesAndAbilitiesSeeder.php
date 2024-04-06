<?php

namespace Database\Seeders;

use Bouncer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateRolesAndAbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin Role
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'Administrator',
        ]);

        // Create Login Ability
        $loginAbility = Bouncer::ability()->firstOrCreate([
            'name' => 'login',
            'title' => 'Login',
        ]);

        // Assign Login Ability to Admin Role
        Bouncer::allow($admin)->to($loginAbility);

        User::create(['name' => 'malak' ,'email' => 'malak@gmail.com' , 'password' => '123456' ]);

        $user = User::where('name', 'malak')->first();

        $user->assign($admin);
       
    }
}
