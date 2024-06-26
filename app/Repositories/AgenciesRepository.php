<?php

namespace App\Repositories;
use App\Models\Agency;
use App\Interfaces\AgenciesRepositoryInterface;

class AgenciesRepository implements AgenciesRepositoryInterface 
{
    public function getAllAgency() 
    {
        return Agency::all();
    }

    
}
