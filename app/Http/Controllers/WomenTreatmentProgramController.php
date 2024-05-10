<?php

namespace App\Http\Controllers;

use App\Models\WomenTreatmentProgram;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\MedicalRecord;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
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
    $program = WomenTreatmentProgram::create([
        'medical_record_id' => $request->input('medical_record_id'),
        'employee_id' => $employee->id,
        'employee_choise_id' => $employee->employeeChoises()->first()->id,
        'acceptance_reason' => $request->input('acceptance_reason'),
        'acceptance_type' => $request->input('acceptance_type'),
        'target_weight' => $request->input('target_weight'),
        'vitamin_a_date' => $request->input('vitamin_a_date'),
        'tetanus_date' => $request->input('tetanus_date'),
        'date' => Carbon::now()->format('Y-m-d'),
        'end_date' => $request->input('end_date'),
        'end_cause' => $request->input('end_cause'),

    ]);


    return $this->created($program);
}

public function getWomenTreatmentsByMedicalCenter($medicalCenterId)
{
    $treatments = WomenTreatmentProgram::whereHas('employeeChoise', function ($query) use ($medicalCenterId) {
        $query->where('medical_center_id', $medicalCenterId);
    })
    ->whereNull('end_cause')
    ->with('MedicalRecord')
    ->get();

    if (!$treatments->count()) {
        return $this->notFound('No women treatment programs found for this medical center');
    }



    return $this->success($treatments);
}

public function getWomenTreatmentProgramByMedicalRecordId(Request $request, $medicalRecordId)
{
    $validator = Validator::make(['medical_record_id' => $medicalRecordId], [
        'medical_record_id' => 'required|integer|exists:medical_records,id',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }

    $treatmentProgram = WomenTreatmentProgram::where('medical_record_id', $medicalRecordId)
        ->with('MedicalRecord')
        ->first();

    if (!$treatmentProgram) {
        return $this->notFound('No women treatment program found for the specified medical record ID.');
    }

    $treatmentProgram->created_at_formatted = $treatmentProgram->created_at->format('d-m-Y');
    return $this->success($treatmentProgram);
}


public function graduateTreatmentProgram(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'end_cause' => 'required|string',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }


    $treatmentProgram = WomenTreatmentProgram::find($id);
    if (!$treatmentProgram) {
        return $this->notFound('No women treatment program found for the specified medical record ID.');
    }

    $treatmentProgram->update([
        'end_date' =>  now()->format('Y-m-d'),
        'end_cause' => $request->input('end_cause'),
    ]);

    return $this->success($treatmentProgram);
}

}


