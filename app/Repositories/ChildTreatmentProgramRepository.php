<?php

namespace App\Repositories;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\ChildTreatmentProgram;
use App\Interfaces\ChildTreatmentProgramRepositoryInterface;

class ChildTreatmentProgramRepository implements ChildTreatmentProgramRepositoryInterface 
{
    public function createTreatment(Request $request){
        $programData = [
            'medical_record_id' => $request->input('medical_record_id'),
            'employee_choise_id' => $request->employeeChoise,
            'employee_id' => $request->employee,
            'program_type' => $request->input('program_type'),
            'acceptance_reason' => $request->input('acceptance_reason'),
            'acceptance_party' => $request->input('acceptance_party'),
            'acceptance_type' => $request->input('acceptance_type'),
            'target_weight' => $request->input('target_weight'),
            'measles_vaccine_received' => $request->input('measles_vaccine_received'),
            'measles_vaccine_date' => $request->input('measles_vaccine_date'),
            'end_date' => $request->input('end_date'),
            'end_cause' => $request->input('end_cause'),
            'date' => $request->date,
        ];
        $program = ChildTreatmentProgram::create($programData);
        return $program;
    }

    public function updateTreatment(Request $request , $id){
        $treatmentProgram = ChildTreatmentProgram::find($id);
        $treatmentProgram->update([
            'end_date' =>  now()->format('Y-m-d'),
            'end_cause' => $request->input('end_cause'),
        ]);

        return $treatmentProgram;
    }

    
}
