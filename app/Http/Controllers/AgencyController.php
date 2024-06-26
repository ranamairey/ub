<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;


class AgencyController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $agencies = Agency::all();
        return $this->success($agencies);
    }

   
}
