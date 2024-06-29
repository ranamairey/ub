<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenter;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class MedicalCenterController extends Controller
{
    use ApiResponseTrait;

  
    public function index()
    {
        $medicalCenters = MedicalCenter::all();
        return $this->success($medicalCenters);
    }

}
