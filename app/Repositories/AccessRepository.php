<?php

namespace App\Repositories;
use App\Models\Access;
use App\Interfaces\AccessRepositoryInterface;

class AccessRepository implements AccessRepositoryInterface 
{
    public function getAllAccess() 
    {
        return Access::all();
    }

    
}
