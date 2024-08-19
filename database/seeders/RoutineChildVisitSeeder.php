<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoutineChildVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_status = ['pregnant', 'lactating', 'non'];
        for ($i = 1; $i <= 20; $i++) {
            DB::table('routine_child_visits')->insert([
                'employee_id' => 2,
                'employee_choise_id' => rand(1, 10),
                'medical_record_id' => rand(6, 12),
                'current_status' =>  $current_status[array_rand($current_status)],
                'health_education' => (bool)random_int(0, 1),
                'weight' => rand(10, 20) + (rand(0, 9) / 10),
                'height' => rand(90, 110) + (rand(0, 9) / 10),
                'sam_acceptance' => (bool)random_int(0, 1),
                'nutritional_survey' => (bool)random_int(0, 1),
                'micronutrients' => (bool)random_int(0, 1),
                'fat_intake' => (bool)random_int(0, 1),
                'high_energy_biscuits' => (bool)random_int(0, 1),
                'z_score' => rand(-3, 3) + (rand(0, 9) / 10),
                'muac' => rand(100, 150),
                'date' => Carbon::now()->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
