<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(CreateRolesAndAbilitiesSeeder::class);
        $this->call(MedicalCentersTableSeeder::class);
        $this->call(AdressesSeeder::class);
        $this->call(AccessSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(AgencySeeder::class);
        $this->call(CoverageSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(PartnerSeeder::class);






}
}
