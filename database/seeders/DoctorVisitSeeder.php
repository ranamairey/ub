<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $results = ['Good', 'Average', 'Bad'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('doctor_visits')->insert([
                'employee_id' => 5, // assuming you have 10 employees
                'employee_choise_id' => rand(1, 10), // assuming you have 10 employee choices
                'medical_record_id' => rand(1, 10), // assuming you have 10 medical records
                'result' => $results[array_rand($results)],
                'health_education' => (bool)random_int(0, 1),
                'health_care' => (bool)random_int(0, 1),
                'date' => Carbon::now()->subDays(rand(1, 365))->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
