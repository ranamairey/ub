<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class MedicalCenterMedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();

        for ($i = 0; $i < 50; $i++) {
            $medicalCenterId = rand(1, 10); // Assuming you have 10 medical centers
            $medicineId = rand(1, 50); // Assuming you have 50 medicines (from MedicineSeeder)

            // Check if both medical center and medicine exist before inserting
            $medicalCenterExists = DB::table('medical_centers')
                ->where('id', $medicalCenterId)
                ->exists();

            $medicineExists = DB::table('medicines')
                ->where('id', $medicineId)
                ->exists();

            if ($medicalCenterExists && $medicineExists) {
                DB::table('medical_center_medicines')->insert([
                    'medical_center_id' => $medicalCenterId,
                    'medicine_id' => $medicineId,
                    'quntity' => $faker->numberBetween(10, 100), // Random quantity between 10 and 100
                ]);
            } else {
                // Log a warning if either medical center or medicine doesn't exist
                \Log::warning("Skipping medical_center_medicine record: Medical center ID $medicalCenterId or medicine ID $medicineId not found.");
            }
        }
    }
}
