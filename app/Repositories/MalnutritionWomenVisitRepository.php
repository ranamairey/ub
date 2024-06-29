<?php

namespace App\Repositories;
use App\Models\Agency;
use Illuminate\Http\Request;
use App\Models\MalnutritionWomenVisit;
use App\Interfaces\MalnutritionWomenVisitRepositoryInterface;



class MalnutritionWomenVisitRepository implements MalnutritionWomenVisitRepositoryInterface 
{
    public function createVisit(Request $request) 
    {
        $visit = MalnutritionWomenVisit::create([
            'employee_id' => $request->employee_id,
            'programs_id' => $request->programs_id,
            'employee_choise_id' => $request->employee_choise_id,
            'muac' => $request->muac,
            'note' => $request->note,
            'current_date' => now()->format('Y-m-d'),
            'next_visit_date' => $request->next_visit_date,

        ]);
        return $visit;
    }

    
}
