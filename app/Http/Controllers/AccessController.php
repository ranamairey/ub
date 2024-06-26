<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Interfaces\AccessRepositoryInterface;


class AccessController extends Controller
{
    use ApiResponseTrait;

    private AccessRepositoryInterface $accessRepository;

    public function __construct(AccessRepositoryInterface $accessRepository) 
    {
        $this->accessRepository = $accessRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesses = $this->accessRepository->getAllAccess();
        return $this->success($accesses);
    }

    
}
