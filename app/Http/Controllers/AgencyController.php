<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Interfaces\AgencyRepositoryInterface;


class AgencyController extends Controller
{
    use ApiResponseTrait;

    private AgencyRepositoryInterface $agencyRepository;

    public function __construct(AgencyRepositoryInterface $agencyRepository) 
    {
        $this->agencyRepository = $agencyRepository;
    }

    public function index()
    {
        $agencies = $this->agencyRepository->getAllAgency();
        return $this->success($agencies);
    }

   
}
