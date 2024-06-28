<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\HealthEducationLecture;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\HealthEducationLectureRepositoryInterface;


class HealthEducationLectureController extends Controller
{
    use ApiResponseTrait;

    private HealthEducationLectureRepositoryInterface $lectureRepository;

    public function __construct(HealthEducationLectureRepositoryInterface $lectureRepository) 
    {
        $this->lectureRepository = $lectureRepository;
    }

    public function createLecture(Request $request)
    {
        $rules = [
            'male_children_number' => 'required|integer|min:0',
            'female_children_number' => 'required|integer|min:0',
            'adult_men_number' => 'required|integer|min:0',
            'adult_women_number' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
            'is_beneficiaries' => 'required|boolean',
            'beneficiary_type' => 'required|string',
            'material_name' => 'required|string',
            'program' => 'required|string',
            'program_category' => 'required|string',
            'date' => 'required|date',
            'partner_id' => ['required', 'integer' , 'exists:partners,id'],
            'access_id' => ['required', 'integer' , 'exists:accesses,id'],
            'agency_id' => ['required', 'integer' , 'exists:agencies,id'],
            'activity_id' => ['required', 'integer' , 'exists:activities,id'],
            'office_id' => ['required', 'integer' , 'exists:offices,id'],
            'coverage_id' => ['required', 'integer' , 'exists:coverages,id'],
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $employee = auth('sanctum')->user();

        $request->employee_id =$employee->id;
        $healthEducationLecture = $this->lectureRepository->createLecture($request);

        $addressData = $request->get('address');


         $healthEducationLecture->addresses()->create([
            'name' => $addressData['name'],
            'subdistrict_id' => $addressData['subdistrict_id'],
        ]);

        $healthEducationLectureId = $healthEducationLecture->id;

        return $this->created($healthEducationLecture);
    }
}
