<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Interfaces\ActivityRepositoryInterface;


class ActivityController extends Controller
{
    use ApiResponseTrait;
    use ApiResponseTrait;
    private ActivityRepositoryInterface $activityRepository;

    public function __construct(ActivityRepositoryInterface $activityRepository) 
    {
        $this->activityRepository = $activityRepository;
    }

    public function index()
    {
        
        $activities = $this->activityRepository->getAllActivity();
        return $this->success($activities);
    }

}
