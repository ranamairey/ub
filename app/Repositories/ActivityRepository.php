<?php

namespace App\Repositories;
use App\Models\Activity;
use App\Interfaces\ActivityRepositoryInterface;

class ActivityRepository implements ActivityRepositoryInterface 
{
    public function getAllActivity() 
    {
        return Activity::all();
    }

    
}
