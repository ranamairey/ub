<?php

namespace App\Http\Controllers;

use App\Models\HealthEducationLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;


class HealthEducationLectureController extends Controller
{
    use ApiResponseTrait;

    public function createLecture(Request $request)
    {
        $rules = [
            'male_children_number' => 'required|integer|min:0',
            'female_children_number' => 'required|integer|min:0',
            'adult_men_number' => 'required|integer|min:0',
            'adult_women_number' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
            'is_beneficiaries' => 'required|boolean',
            'beneficiary_type' => ['required', 'in:returnees,internally_displaced,host_community'],
            'material_name' => 'required|string',
            'program' => 'required|string',
            'program_category' => 'required|string',
            'date' => 'required|date',
            'partner_id' => 'required|string',
            'access_id' => 'required|string',
            'agency_id' => 'required|string',
            'activity_id' => 'required|string',
            'office_id' => 'required|string',
            'coverage_id' => 'required|string',
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $employee = auth('sanctum')->user();

        $healthEducationLecture = HealthEducationLecture::create([
            'employee_id' => $employee->id,
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

        $addressData = $request->get('address');


         $healthEducationLecture->addresses()->create([
            'name' => $addressData['name'],
            'subdistrict_id' => $addressData['subdistrict_id'],
        ]);

        $healthEducationLectureId = $healthEducationLecture->id;

        return $this->created($healthEducationLecture);
    }
}
