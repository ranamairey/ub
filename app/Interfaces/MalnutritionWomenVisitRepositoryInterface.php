<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface MalnutritionWomenVisitRepositoryInterface
{
    public function createVisit(Request $request);
    
}
