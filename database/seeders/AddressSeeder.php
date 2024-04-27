<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\MedicalRecord;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    // Get all medical records
    $medicalRecords = MedicalRecord::all();

    foreach ($medicalRecords as $medicalRecord) {
        
        Address::create([
            'name' => 'xxxxxxx', 
            'subdistrict_id' => 1, 
            'addressable_id' => $medicalRecord->id,
            'addressable_type' => 'App\Models\MedicalRecord',
        ]);
    }
}
}
