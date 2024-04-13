<?php

namespace Database\Seeders;

use Bouncer;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class CreateRolesAndAbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

        {
            // Create Doctor Role
            $doctor = Bouncer::role()->firstOrCreate([
                'name' => 'doctor',
                'title' => 'Doctor',
            ]);

            // Create Nutritionist Role
            $nutritionist = Bouncer::role()->firstOrCreate([
                'name' => 'nutritionist',
                'title' => 'Nutritionist',
            ]);

            // Create Receptionist Role
            $receptionist = Bouncer::role()->firstOrCreate([
                'name' => 'receptionist',
                'title' => 'Receptionist',
            ]);

            // Create Statistics Employee Role
            $statisticsEmployee = Bouncer::role()->firstOrCreate([
                'name' => 'statistics-employee',
                'title' => 'Statistics Employee',
            ]);

            // Create Pharmacist Role
            $pharmacist = Bouncer::role()->firstOrCreate([
                'name' => 'pharmacist',
                'title' => 'Pharmacist',
            ]);

            $adminData = [
                'name' => 'Admin',
                'phone_number' => '1234567890',
                'user_name' => 'admin',
                'password' => Hash::make('secret123'),
                'active' => true,
            ];

            $admin = Employee::create($adminData);


                $admin->assign($statisticsEmployee);


}
    }
