<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create('ar_JO');

        $medicineTypes = ['Ordinary', 'Nutrition'];

        for ($i = 0; $i < 50; $i++) {
            $employeeId = DB::table('employees')->pluck('id')->random(); // Random selection
            $randomTypeIndex = array_rand($medicineTypes);  // Random index in medicineTypes

            DB::table('medicines')->insert([
                'name' => $faker->unique()->name . ' دواء',
                'type' => $medicineTypes[$randomTypeIndex],  // Use random type from array
                'scientific_name' => $faker->word . ' (scientific name)',
                'titer' => $faker->randomNumber(2),
                'code' => $faker->unique()->randomNumber(8),
                'unit' => rand(1, 10),
                'employee_id' => $employeeId,
            ]);
        }
    }
}
