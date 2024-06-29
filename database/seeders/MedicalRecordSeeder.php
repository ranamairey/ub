<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $femaleNames = [
            "آية", "نور", "سارة", "شيماء", "ريم", "فرح", "ملك", "حنين", "جوري", "جنى"
        ];

        $maleNames = [
            "محمد", "عمر", "علي", "خالد", "ياسين", "حسام", "أحمد", "عبدالله", "رامي", "باسم"
        ];

        $familyNames = [
            "الجارحي", "العنزي", "السعدي", "المطيري", "الحربي", "الشمري", "القحطاني", "المانعي", "الدوسري", "العجيمي"
        ];
  // Always set gender to "Female"
  $gender = 'Female';

  for ($i = 0; $i < 5; $i++) {
      // Randomly choose a name from the femaleNames array
      $name = $femaleNames[array_rand($femaleNames)];

      // Ensure different names for mothers and fathers
      $motherName = $femaleNames[array_rand($femaleNames)];
      $fatherName = $maleNames[array_rand($maleNames)]; // Still use male names for fathers

      $familyName = $familyNames[array_rand($familyNames)];

      DB::table('medical_records')->insert([
          'employee_id' => 6,
          'account_id' => null,
          'category' => 'pregnant',
          'name' => $name ,
          'mother_name' => $motherName,
          'father_name' => $fatherName,
          'last_name' => $familyName ,
          'gender' => $gender,
          'phone_number' => '123456789' . $i,
               'residence_status' => ['Resident' , 'Immigrant' , 'Returnee'][array_rand(['Resident' , 'Immigrant' , 'Returnee'])],
                'special_needs' => rand(0, 1),
                'related_person' => $fatherName,
                'related_person_phone_number' => "00000000",
                'birth_date' => date('Y-m-d', strtotime('-' . rand(18, 40) . ' years')),
            ]);
        }
        for ($i = 5; $i < 10; $i++) {
            // Randomly choose gender
            $gender = (rand(0, 1) === 0) ? 'Female' : 'Male';

            // Choose name pool based on gender
            $namePool = ($gender === 'Female') ? $femaleNames : $maleNames;

            // Choose a random name from the appropriate pool
            $name = $namePool[array_rand($namePool)];

            // Ensure different names for mothers and fathers
            $motherName = $femaleNames[array_rand($femaleNames)];
            $fatherName = $maleNames[array_rand($maleNames)];

            $familyName = $familyNames[array_rand($familyNames)];

            $category = ($gender === 'Female') ? 'pregnant' : 'child'; // Set category based on gender

            DB::table('medical_records')->insert([
                'employee_id' => 6,
                'account_id' => null,
                'category' => "child", // Use the determined category
                'name' => $name ,
                'mother_name' => $motherName,
                'father_name' => $fatherName,
                'last_name' => $familyName , // Use family name with sequence
                'gender' => $gender,
                'phone_number' => '123456789',
                'residence_status' => ['Resident' , 'Immigrant' , 'Returnee'][array_rand(['Resident' , 'Immigrant' , 'Returnee'])],
                'special_needs' => rand(0, 1),
                'related_person' => $fatherName,
                'related_person_phone_number' => "00000000",
                'birth_date' => date('Y-m-d', strtotime('-' . rand(1, 12) . ' years')),
            ]);
        }

    }

}
