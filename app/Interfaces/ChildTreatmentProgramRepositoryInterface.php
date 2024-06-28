<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface ChildTreatmentProgramRepositoryInterface         
{
  
    public function createTreatment(Request $request);

    public function updateTreatment(Request $request , $id);

}