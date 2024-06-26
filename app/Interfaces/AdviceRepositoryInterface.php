<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AdviceRepositoryInterface 
{
    
    public function createAdvice(Request $request);
    
}
