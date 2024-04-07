<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Seed governorates
         $governorates = [
            ['name' => 'درعا'],
        ];
        DB::table('governorates')->insert($governorates);

        // Seed districts for each governorate
        $districts = [
            ['name' => 'درعا البلد', 'governorate_id' => 1],
            ['name' => 'الصنمين', 'governorate_id' => 1],
            ['name' => 'إزرع', 'governorate_id' => 1],
            ['name' => 'جاسم', 'governorate_id' => 1],
            ['name' => 'الحارة', 'governorate_id' => 1],
            ['name' => 'الشيخ مسكين', 'governorate_id' => 1],
            ['name' => 'بصرى الشام', 'governorate_id' => 1],
            ['name' => 'محجة', 'governorate_id' => 1],
        ];
        DB::table('districts')->insert($districts);

        // Seed subdistricts for each district
        $subdistricts = [
            ['name' => 'القصبة', 'district_id' => 1],
            ['name' => 'المطار', 'district_id' => 1],
            ['name' => 'الكرك الشرقي', 'district_id' => 2],
            ['name' => 'الكرك الغربي', 'district_id' => 2],
            ['name' => 'الشجرة', 'district_id' => 3],
            ['name' => 'غرز', 'district_id' => 3],
            ['name' => 'جاسم البلد', 'district_id' => 4],
            ['name' => 'اللجاة', 'district_id' => 4],
            ['name' => 'الحارة البلد', 'district_id' => 5],
            ['name' => 'الطيبة', 'district_id' => 5],
            ['name' => 'الشيخ مسكين البلد', 'district_id' => 6],
            ['name' => 'تل شهاب', 'district_id' => 6],
            ['name' => 'بصرى الشام البلد', 'district_id' => 7],
            ['name' => 'المزيريب', 'district_id' => 7],
            ['name' => 'محجة البلد', 'district_id' => 8],
            ['name' => 'تل الذهب', 'district_id' => 8],
        ];
        DB::table('subdistricts')->insert($subdistricts);

      

    }
}
