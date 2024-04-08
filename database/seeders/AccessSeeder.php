<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accesses=[
            ['name' => 'Regular Program'],
            ['name' => 'UNICEF Crossline Convoy'],
            ['name' => 'Inter-Agency Convoy'],
            ['name' => 'Un-Accompanied Convoy'],
            ['name' => 'UNICEF Non Crossline Convoy'],
            ['name' => 'Cross Border'],
            ['name' => 'Air Drop'],
            ['name' => 'Emergency Response'],
        ];
        DB::table('accesses')->insert($accesses);

    }
}
