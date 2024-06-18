<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoutineWomenVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $currentStatuses = ['mam', 'normal'];
        $statusTypes = ['pregnant', 'lactating', 'non'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('routine_women_visits')->insert([
                'employee_id' => 1, // assuming you have 10 employees
                'employee_choise_id' => rand(1, 10), // assuming you have 10 employee choices
                'medical_record_id' => rand(1, 10), // assuming you have 10 medical records
                'current_status' => $currentStatuses[array_rand($currentStatuses)],
                'IYCF' => (bool)random_int(0, 1),
                'nutritional_survey' => (bool)random_int(0, 1),
                'micronutrients' => (bool)random_int(0, 1),
                'high_energy_biscuits' => (bool)random_int(0, 1),
                'health_education' => (bool)random_int(0, 1),
                'weight' => rand(50, 100) / 10, // random weight between 5.0 and 10.0
                'status_type' => $statusTypes[array_rand($statusTypes)],
                'date' => Carbon::now()->subDays(rand(1, 365))->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
