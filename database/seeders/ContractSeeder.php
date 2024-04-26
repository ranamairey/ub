<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Support\Str;
use App\Models\MedicalCenter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // Get all employees and medical centers
        $employees = Employee::all();
        $medicalCenters = MedicalCenter::all();

        // Check if employees and medical centers exist
        if ($employees->isEmpty() || $medicalCenters->isEmpty()) {
            return;
        }

        // Create contracts for each employee
        for ($i = 0; $i < 20; $i++) {
            // Randomly assign a medical center
            $medicalCenter = $medicalCenters->random();
            $employee = $employees->random();
            DB::table('contracts')->insert([
                'employee_id' => $employee->id,
                'medical_center_id' => $medicalCenter->id,
                'expiration_date' => Carbon::now()->addHours($i),
                'contract_value' => rand(1000, 5000),
                'certificate' => Str::random(10),
                'is_valid' => rand(0, 1),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
