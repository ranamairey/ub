<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface DoctorVisitRepositoryInterface 
{
    public function createDoctorVisit(Request $request);
    
}
