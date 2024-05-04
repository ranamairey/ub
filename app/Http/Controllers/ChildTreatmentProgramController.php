<?php

namespace App\Http\Controllers;

use App\Models\ChildTreatmentProgram;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\MedicalRecord;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;



class ChildTreatmentProgramController extends Controller
{

    use ApiResponseTrait;


    public function createChildTreatmentProgram(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'medical_record_id' => 'required|integer|exists:medical_records,id',
            'program_type' => 'required|in:TSFP,OTP',
            'acceptance_reason' => 'required|string',
            'acceptance_party' => 'required|string|in:another-TSFP,OTP,Re-acceptance,SC,Community',
            'acceptance_type' => 'required|in:new,old',
            'target_weight' => 'required|numeric',
            'measles_vaccine_received' => 'nullable|boolean',
            'measles_vaccine_date' => 'nullable|date',
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
            'employee_choise_id' => $employee->employeeChoises()->first()->id,
            'employee_id' => $employee->id,
            'program_type' => $request->input('program_type'),
            'acceptance_reason' => $request->input('acceptance_reason'),
            'acceptance_party' => $request->input('acceptance_party'),
            'acceptance_type' => $request->input('acceptance_type'),
            'target_weight' => $request->input('target_weight'),
            'measles_vaccine_received' => $request->input('measles_vaccine_received'),
            'measles_vaccine_date' => $request->input('measles_vaccine_date'),
            'end_date' => $request->input('end_date'),
            'end_cause' => $request->input('end_cause'),
        ];

        $program = ChildTreatmentProgram::create($programData);

        return $this->created($program);
    }

    public function getChildTreatmentsByMedicalCenter($medicalCenterId)
{
    $treatments = ChildTreatmentProgram::whereHas('employeeChoise', function ($query) use ($medicalCenterId) {
        $query->where('medical_center_id', $medicalCenterId);
    })->with('MedicalRecord')
    ->get();;


    if (!$treatments->count()) {
        return $this->notFound('No child treatment programs found for this medical center');
    }


    return $this->success($treatments);
}

public function getChildTreatmentProgramByMedicalRecordId(Request $request, $medicalRecordId)
{
    $validator = Validator::make(['medical_record_id' => $medicalRecordId], [
        'medical_record_id' => 'required|integer|exists:medical_records,id',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }

    $treatmentProgram = ChildTreatmentProgram::where('medical_record_id', $medicalRecordId)
        ->with('MedicalRecord')
        ->first();

    if (!$treatmentProgram) {
        return $this->notFound('No child treatment program found for the specified medical record ID.');
    }

    return $this->success($treatmentProgram);
}


public function graduateChildTreatmentProgram(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'end_cause' => 'required|string',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }


    $treatmentProgram = ChildTreatmentProgram::find($id);
    if (!$treatmentProgram) {
        return $this->notFound('No child treatment program found for the specified medical record ID.');
    }

    $treatmentProgram->update([
        'end_date' =>  now()->format('Y-m-d'),
        'end_cause' => $request->input('end_cause'),
    ]);

    return $this->success($treatmentProgram);
}


}
