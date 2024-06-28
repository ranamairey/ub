<?php

namespace App\Repositories;
use App\Models\Agency;
use App\Models\DoctorVisit;
use Illuminate\Http\Request;
use App\Models\MalnutritionChildVisit;
use App\Interfaces\DoctorVisitRepositoryInterface;



class MalnutritionChildVisitRepository implements MalnutritionChildVisitRepositoryInterface 
{
    public function createVisit(Request $request){
        $visit = MalnutritionChildVisit::create([
            'employee_id' => $request->employee_id,
            'programs_id' => $request->programs_id,
            'employee_choise_id' => $request->employee_choise_id,
            'edema' => $request->edema,
            'weight' => $request->weight,
            'height' => $request->height,
            'muac' => $request->muac,
            'z_score' => $request->z_score,
            'note' => $request->note,
            'current_date' => now()->format('Y-m-d'),
            'next_visit_date' => $request->next_visit_date,

        ]);
        return $visit;
    }
    
}
