<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;



class MedicalCentersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medical_centers2 = [

            [
                'name' => 'المركز الصحي الثابت في درعا السد',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('medical_centers')->insert($medical_centers2);

        $medical_centers = [

            [
                'name' => 'المركز الصحي الثابت في درعا البلد',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('medical_centers')->insert($medical_centers);
    }
    }

