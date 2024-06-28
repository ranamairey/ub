<?php

namespace App\Http\Controllers;

use App\Models\Coverage;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;


class CoverageController extends Controller
{
    use ApiResponseTrait;

    
    public function index()
    {
        $coverages = Coverage::all();
        return $this->success($coverages);
    }

}
