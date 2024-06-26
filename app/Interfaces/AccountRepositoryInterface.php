<?php

namespace App\Interfaces;
use Illuminate\Http\Request;

interface AccountRepositoryInterface 
          
{
    public function createAccount(Request $request);
    
}