<?php

namespace App\Http\Controllers;

use App\Models\Coverage;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;


class CoverageController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coverages = Coverage::all();
        return $this->success($coverages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coverage  $coverage
     * @return \Illuminate\Http\Response
     */
    public function show(Coverage $coverage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coverage  $coverage
     * @return \Illuminate\Http\Response
     */
    public function edit(Coverage $coverage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coverage  $coverage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coverage $coverage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coverage  $coverage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coverage $coverage)
    {
        //
    }
}
