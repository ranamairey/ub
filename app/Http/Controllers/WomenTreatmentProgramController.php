<?php

namespace App\Http\Controllers;

use App\Models\WomenTreatmentProgram;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\MedicalRecord;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class WomenTreatmentProgramController extends Controller
{

    use ApiResponseTrait;


    public function createWomenTreatmentProgram(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'medical_record_id' => 'required|integer|exists:medical_records,id',
        'acceptance_reason' => 'required|string',
        'acceptance_type' => 'required|in:new,old',
        'target_weight' => 'required|numeric',
        'tetanus_date' => 'nullable|date',
        'vitamin_a_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'end_cause' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }


    $employee = auth('sanctum')->user();
    if (!$employee) {
        return $this->unauthorized('You are not logged in');
    }

    if (!MedicalRecord::where('id', $request->input('medical_record_id'))->exists()) {
        return $this->unprocessable($request->all(), 'The specified medical record does not exist.');
    }

    $programData = [
        'medical_record_id' => $request->input('medical_record_id'),
        'employee_id' => $employee->id,
        'employee_choise_id' => $employee->employeeChoises()->first()->id,
        'acceptance_reason' => $request->input('acceptance_reason'),
        'acceptance_type' => $request->input('acceptance_type'),
        'target_weight' => $request->input('target_weight'),
        'vitamin_a_date' => $request->input('vitamin_a_date'),
        'tetanus_date' => $request->input('tetanus_date'),
        'end_date' => $request->input('end_date'),
        'end_cause' => $request->input('end_cause'),
    ];

    $program = WomenTreatmentProgram::create($programData);

    return $this->created($program);
}
}


