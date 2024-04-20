<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        for ($i = 0; $i < 5; $i++) {
            DB::table('medical_records')->insert([
                'employee_id' => 3, 
                'account_id' => null, 
                'category' => 'pregnant',
                'name' => "Malak" . $i,
                'mother_name' => "Samah". $i,
                'father_name' => "Ayman" . $i,
                'last_name' => "Sarhan" . $i,
                'gender' => ['Male', 'Female'][array_rand(['Male', 'Female'])],
                'phone_number' => '123456789' . $i,
                'residence_status' => ['Resident' , 'Immigrant' , 'Returnee'][array_rand(['Resident' , 'Immigrant' , 'Returnee'])],
                'special_needs' => rand(0, 1),
                'related_person' => "mmmm",
                'related_person_phone_number' => "00000000",
                'birth_date' => date('Y-m-d', strtotime('-'.rand(18,40).' years')),
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            DB::table('medical_records')->insert([
                'employee_id' => 5, 
                'account_id' => null,
                'category' => 'child',
                'name' => "Rana" . $i,
                'mother_name' => "Mom". $i,
                'father_name' => "Naser" . $i,
                'last_name' => "Mar" . $i,
                'gender' => ['Male', 'Female'][array_rand(['Male', 'Female'])],
                'phone_number' => '123456789' . $i,
                'residence_status' => ['Resident' , 'Immigrant' , 'Returnee'][array_rand(['Resident' , 'Immigrant' , 'Returnee'])],
                'special_needs' => rand(0, 1),
                'related_person' => "mmmm",
                'related_person_phone_number' => "00000000",
                'birth_date' => date('Y-m-d', strtotime('-'.rand(0,5).' years')),
                'created_at' => Carbon::now()->addHours($i),
            ]);
        }
    }
}
