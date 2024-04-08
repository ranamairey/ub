<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offices=[
            ['name' => 'Amman'],
            ['name' => 'Damascus'],
            ['name' => 'Gaziantep'],
        ];
        DB::table('offices')->insert($offices);
    }
}
