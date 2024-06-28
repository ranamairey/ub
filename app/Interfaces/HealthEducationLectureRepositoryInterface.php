<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface HealthEducationLectureRepositoryInterface 
{
    public function createLecture(Request $request);
    
}
