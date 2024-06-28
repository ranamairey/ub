<?php

namespace App\Repositories;
use App\Models\Advice;
use App\Models\Activity;
use Illuminate\Http\Request;

use App\Models\HealthEducationLecture;
use App\Interfaces\HealthEducationLectureRepositoryInterface;


class HealthEducationLectureRepository implements HealthEducationLectureRepositoryInterface 
{
   public function createLecture(Request $request){
    $healthEducationLecture = HealthEducationLecture::create([
        'employee_id' => $request->employee_id,
        'male_children_number' => $request->get('male_children_number'),
        'female_children_number' => $request->get('female_children_number'),
        'adult_men_number' => $request->get('adult_men_number'),
        'adult_women_number' => $request->get('adult_women_number'),
        'total' => $request->get('total'),
        'is_beneficiaries' => $request->get('is_beneficiaries'),
        'beneficiary_type' => $request->get('beneficiary_type'),
        'material_name' => $request->get('material_name'),
        'program' => $request->get('program'),
        'program_category' => $request->get('program_category'),
        'date' => $request->get('date'),
        'activity_id' => $request->get('activity_id'),
        'partner_id' => $request->get('partner_id'),
        'access_id' => $request->get('access_id'),
        'agency_id' => $request->get('agency_id'),
        'office_id' => $request->get('office_id'),
        'coverage_id' => $request->get('coverage_id'),
    ]);
    return $healthEducationLecture;
   }
    
}
