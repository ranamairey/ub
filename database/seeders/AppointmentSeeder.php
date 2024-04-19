<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $medicalRecordIds = DB::table('medical_records')->pluck('id');
        $employeeIds = DB::table('employees')->pluck('id');

        for ($i = 0; $i < 10; $i++) {
            $employeeId = rand(1, 2);
            $employeeType = $employeeId === 1 ? 'Nutritionist' : 'Doctor';
            DB::table('appointments')->insert([
                'medical_record_id' => $medicalRecordIds[rand(0, count($medicalRecordIds) - 1)],
                'receptionist_id' => 3,
                'employee_id' => $employeeId,
                'employee_type' => $employeeType,

            ]);
        }
    }
}
