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
            'date' => Carbon::now()->format('Y-m-d'),
        ];

        $program = ChildTreatmentProgram::create($programData);

        return $this->created($program);
    }

    public function getChildTreatmentsByMedicalCenter($medicalCenterId)
{
    $treatments = ChildTreatmentProgram::whereHas('employeeChoise', function ($query) use ($medicalCenterId) {
        $query->where('medical_center_id', $medicalCenterId);
    })
    ->whereNull('end_cause')
    ->with('MedicalRecord')
    ->get();


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


public function transsformChildTreatmentProgram(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'end_cause' => 'required|string',
        'new_program_type' => 'required|in:tsfp,otp',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }

    $treatmentProgram = ChildTreatmentProgram::find($id);
    if (!$treatmentProgram) {
        return $this->notFound('No child treatment program found for the specified medical record ID.');
    }
    if ($treatmentProgram->end_date) {
        return $this->unprocessable('The child treatment program is already terminated.');
    }

    $currentProgramType = $treatmentProgram->program_type;
    if ($request->input('new_program_type') === $currentProgramType) {
        return $this->unprocessable('Cannot transfer to the same program type.');
    }

    switch ($currentProgramType) {
        case 'otp':
            if ($request->input('end_cause') !== 'Referral to tsfp') {
                return $this->unprocessable('Invalid transfer reason for OTP program.');
            }
            $newProgramType = 'tsfp';
            break;
        case 'tsfp':
            if ($request->input('end_cause') !== 'Referral to otp') {
                return $this->unprocessable('Invalid transfer reason for TSFP program.');
            }
            $newProgramType = 'otp';
            break;
        default:
            return $this->unprocessable('Invalid current program type.');
    }


    $newTreatmentProgramData = [
        'medical_record_id' => $treatmentProgram->medical_record_id,
        'employee_choise_id' => $treatmentProgram->employee_choise_id,
        'employee_id' => $treatmentProgram->employee_id,
        'program_type' => $newProgramType,
        'acceptance_reason' => $request->input('end_cause'),
        'acceptance_party' => 'Re-acceptance',
        'acceptance_type' => 'old',
        'target_weight' => $treatmentProgram->target_weight,
        'measles_vaccine_received' => $treatmentProgram->measles_vaccine_received,
        'measles_vaccine_date' => $treatmentProgram->measles_vaccine_date,


    ];


    $newTreatmentProgram = ChildTreatmentProgram::create($newTreatmentProgramData);

    $treatmentProgram->update([
        'end_date' => now()->format('Y-m-d'),
        'end_cause' => $request->input('end_cause'),
    ]);


    $lastVisitData = $treatmentProgram->lastVisit;
    if ($lastVisitData) {
        $newTreatmentProgram->lastVisit()->update($lastVisitData);
    }

    return $this->success([
        'graduated_program' => $treatmentProgram,
        'new_program' => $newTreatmentProgram,
    ]);
}



}
