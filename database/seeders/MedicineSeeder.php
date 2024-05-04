<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     * @return void
     */
    public function run()
    {
        $medicines = [
            ['name'  => "اموكسيل فموي بودرة", 'type' => "", 'scientific_name' => "Amoxici.pdr/oral sus" , 'titer' => '125mg/5ml/BOT-100ml/125مغ' ,'code' => 00 ,'unit' => 4 ]




        ];
    }
}
