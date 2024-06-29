<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppointmentSeeder extends Seeder
{
  public function run()
  {
    $medicalRecordIds = DB::table('medical_records')->pluck('id');

    // Specific employee IDs for appointments
    $employeeIDs = [1, 4]; // Replace with actual IDs if needed

    foreach ($employeeIDs as $employeeId) {
      $employeeType = $employeeId === 1 ? 'Nutritionist' : ($employeeId === 4 ? 'Doctor' : 'Other'); // Specific assignment

      for ($i = 0; $i < 5; $i++) { // Schedule 5 appointments for each employee
        DB::table('appointments')->insert([
          'medical_record_id' => $medicalRecordIds[rand(0, 4)],
          'receptionist_id' => 5,
          'employee_id' => $employeeId,
          'employee_type' => $employeeType,
        ]);
      }
    }
     // Specific employee IDs for appointments
     $employeeIDs2 = [2, 5]; // Replace with actual IDs if needed

     foreach ($employeeIDs2 as $employeeId) {
       $employeeType = $employeeId === 2 ? 'Nutritionist' : ($employeeId === 5 ? 'Doctor' : 'Other'); // Specific assignment

       for ($i = 0; $i < 5; $i++) { 
         DB::table('appointments')->insert([
           'medical_record_id' => $medicalRecordIds[rand(5, 9)],
           'receptionist_id' => 5,
           'employee_id' => $employeeId,
           'employee_type' => $employeeType,
         ]);
       }
     }
  }
}
