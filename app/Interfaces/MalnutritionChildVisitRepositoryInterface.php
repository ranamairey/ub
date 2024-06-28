<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface MalnutritionChildVisitRepositoryInterface
{
    public function createVisit(Request $request);
    
}
