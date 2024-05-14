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
            'App/Model/RoutineWomenVisit',
            // 'App/Model/MalnutritionWomenVisit',
            'App/Model/DoctorVisit',
            // 'App/Model/MalnutritionChildVisit',
            // 'App/Model/RoutineChildVisit'
        ];

        for ($i = 0; $i < 50; $i++) {
            DB::table('medicine_orders')->insert([
                'employee_id' => [1, 5][array_rand([0, 1])],
                'medicine_orderable_id' => rand(1, 10), // assuming you have 10 orderable medicines
                'medicine_orderable_type' => $medicineOrderableTypes[array_rand($medicineOrderableTypes)],
                'quantity' => rand(1, 100),
                'activity_id' => rand(1, 10), // assuming you have 10 activities
                'medical_center_medicine_id' => rand(1, 8), // assuming you have 10 medical center medicines
                'is_aprroved' => 0,
            ]);
        }
    }
}
