<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coverages=[
            ['name' => 'Admin1'],
            ['name' => 'Admin2'],
            ['name' => 'Admin3'],
            ['name' => 'Admin4'],
        ];
        DB::table('coverages')->insert($coverages);

    }
}
