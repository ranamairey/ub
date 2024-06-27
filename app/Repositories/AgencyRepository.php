<?php

namespace App\Repositories;
use App\Models\Agency;
use App\Interfaces\AgencyRepositoryInterface;


class AgencyRepository implements AgencyRepositoryInterface 
{
    public function getAllAgency() 
    {
        return Agency::all();
    }

    
}
