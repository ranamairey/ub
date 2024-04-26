<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\AccessSeeder;
use Database\Seeders\AgencySeeder;
use Database\Seeders\OfficeSeeder;
use Database\Seeders\PartnerSeeder;
use Database\Seeders\ActivitySeeder;
use Database\Seeders\AdressesSeeder;
use Database\Seeders\CoverageSeeder;
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\MedicalRecordSeeder;
use Database\Seeders\MedicalCentersTableSeeder;
use Database\Seeders\CreateRolesAndAbilitiesSeeder;

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
        $this->call(MedicalRecordSeeder::class);
        $this->call(AppointmentSeeder::class);
        $this->call(EmployeeChoiseSeeder::class);
        $this->call(WomenTreatmentProgramSeeder::class);
        $this->call(ChildTreatmentProgramSeeder::class);
        $this->call(ContractSeeder::class);




        






}
}
