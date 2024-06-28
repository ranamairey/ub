<?php

namespace App\Repositories;
use App\Models\Agency;
use App\Models\DoctorVisit;
use Illuminate\Http\Request;
use App\Interfaces\DoctorVisitRepositoryInterface;



class DoctorVisitRepository implements DoctorVisitRepositoryInterface 
{
    public function createDoctorVisit(Request $request){
        $doctorVisit = DoctorVisit::create([
            'employee_id' => $request->employee_id,
            'employee_choise_id' => $request->employee_choise,
            'medical_record_id' => $request->input('medical_record_id'),
            'result' => $request->input('result'),
            'date' => $request->input('date'),
            'health_education' => $request->input('health_education'),
            'health_care' => $request->input('health_care'),
        ]);
        return $doctorVisit;
    }
    
}
