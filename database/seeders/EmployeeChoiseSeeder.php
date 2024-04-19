<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeChoiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $employeeIds = DB::table('employees')->pluck('id');
        $medicalCenterIds = DB::table('medical_centers')->pluck('id');
        $coverageIds = DB::table('coverages')->pluck('id');
        $officeIds = DB::table('offices')->pluck('id');
        $activityIds = DB::table('activities')->pluck('id');
        $agencyIds = DB::table('agencies')->pluck('id');
        $accessIds = DB::table('accesses')->pluck('id');
        $partnerIds = DB::table('partners')->pluck('id');

        for ($i = 0; $i < 20; $i++) {
            DB::table('employee_choises')->insert([
                'employee_id' => $employeeIds[rand(0, count($employeeIds) - 1)],
                'medical_center_id' => $medicalCenterIds[rand(0, count($medicalCenterIds) - 1)],
                'coverage_id' => $coverageIds[rand(0, count($coverageIds) - 1)],
                'office_id' => $officeIds[rand(0, count($officeIds) - 1)],
                'activity_id' => $activityIds[rand(0, count($activityIds) - 1)],
                'agency_id' => $agencyIds[rand(0, count($agencyIds) - 1)],
                'access_id' => $accessIds[rand(0, count($accessIds) - 1)],
                'partner_id' => $partnerIds[rand(0, count($partnerIds) - 1)],
            ]);
        }
    }
}
