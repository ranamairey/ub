<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    
    public function index()
    {
        $stores = District::all();

        return response()->json($stores);
    }

    
}
