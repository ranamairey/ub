<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WomenTreatmentProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medicalRecordIds = DB::table('medical_records')->where('category', 'pregnant')->pluck('id');

        for ($i = 0; $i < 10; $i++) {
            DB::table('women_treatment_programs')->insert([
                'medical_record_id' => $medicalRecordIds[rand(0, count($medicalRecordIds) - 1)],
                'employee_id' => rand(1, 2),
                'employee_choise_id' => 1,
                'acceptance_type' => ['new', 'old'][array_rand(['new', 'old'])],
                'acceptance_reason' => "Blablablablablabla",
                'target_weight' => 80,
                'tetanus_date' => "2022-2-2",
                'vitamin_a_date' => "2023-3-3",
                'end_date' => "2021-1-1",
                'end_cause' => "trdfghjklkijuytfrgthjk",
                // Add the 'date' value
                'date' => Carbon::now()->addHours($i)->format('Y-m-d'), // Format date as YYYY-MM-DD
                'created_at' => Carbon::now()->addHours($i),
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            DB::table('women_treatment_programs')->insert([
                'medical_record_id' => $medicalRecordIds[rand(0, count($medicalRecordIds) - 1)],
                'employee_id' => rand(1, 2),
                'employee_choise_id' => 1,
                'acceptance_type' => ['new', 'old'][array_rand(['new', 'old'])],
                'acceptance_reason' => "Blablablablablabla",
                'target_weight' => 100,
                'tetanus_date' => "2023-3-3",
                'vitamin_a_date' => "2022-2-2",
                'end_date' => null,
                'end_cause' => null,
                'date' => Carbon::now()->addHours($i)->format('Y-m-d'), // Add formatted date
                'created_at' => Carbon::now()->addHours($i),
            ]);
        }
    }
}
