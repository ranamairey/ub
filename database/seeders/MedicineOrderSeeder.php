<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MedicineOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $medicineOrderableTypes = [
            RoutineWomenVisit::class,
            // MalnutritionWomenVisit::class,
            DoctorVisit::class,
            // MalnutritionChildVisit::class,
            // RoutineChildVisit::class
        ];

        $employeeCount = DB::table('employees')->count();
        $activityCount = DB::table('activities')->count();
        $medicalCenterMedicineCount = DB::table('medical_center_medicines')->count();

        for ($i = 0; $i < 50; $i++) {
            DB::table('medicine_orders')->insert([
                'employee_id' => rand(1, $employeeCount),
                'medicine_orderable_id' => rand(1, 10), // assuming you have 10 orderable medicines
                'medicine_orderable_type' => $medicineOrderableTypes[array_rand($medicineOrderableTypes)],
                'quantity' => rand(1, 100),
                'activity_id' => rand(1, $activityCount),
                'medical_center_medicine_id' => rand(1, $medicalCenterMedicineCount),
                'is_aprroved' => 0,
            ]);
        }
    }
}
