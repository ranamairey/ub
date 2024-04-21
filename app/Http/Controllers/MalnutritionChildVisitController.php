<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\ChildTreatmentProgram;
use App\Models\MalnutritionChildVisit;
use Illuminate\Support\Facades\Validator;


class MalnutritionChildVisitController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($programId)
    {
        $visits = MalnutritionChildVisit::where('programs_id', $programId)->get();

        if (!$visits->count()) {
            return $this->notFound('No visits found for program ID: ' . $programId);
        }

        return $this->success($visits);
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

    $validator = Validator::make($request->all(), [
        'programs_id' => 'required|exists:child_treatment_programs,id',
        'edema' => 'required|boolean',
        'weight' => 'required|numeric',
        'height' => 'required|numeric',
        'muac' => 'required|integer',
        'z_score' => 'required|numeric',
        'note' => 'required|string',
        'current_date' => 'required|date_format:Y-m-d',
        'next_visit_date' => 'required|date_format:Y-m-d',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }
    $program =ChildTreatmentProgram::find($request->programs_id);

    if(!$program){
        return $this->notFound($request->programs_id , "Program not found");
    }
    $employee_id = auth('sanctum')->user()->id;
    $employee_choise_id = EmployeeChoise::where('employee_id', $employee_id)->latest('created_at')->first()->id;
    $visit = MalnutritionChildVisit::create([
        'employee_id' => $employee_id,
        'programs_id' => $request->programs_id,
        'employee_choise_id' => $employee_choise_id,
        'edema' => $request->edema,
        'weight' => $request->weight,
        'height' => $request->height,
        'muac' => $request->muac,
        'z_score' => $request->z_score,
        'note' => $request->note,
        'current_date' => $request->current_date,
        'next_visit_date' => $request->next_visit_date,

    ]);
    return $this->created($visit);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MalnutritionChildVisit  $malnutritionChildVisit
     * @return \Illuminate\Http\Response
     */
    public function show(MalnutritionChildVisit $malnutritionChildVisit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MalnutritionChildVisit  $malnutritionChildVisit
     * @return \Illuminate\Http\Response
     */
    public function edit(MalnutritionChildVisit $malnutritionChildVisit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MalnutritionChildVisit  $malnutritionChildVisit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MalnutritionChildVisit $malnutritionChildVisit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MalnutritionChildVisit  $malnutritionChildVisit
     * @return \Illuminate\Http\Response
     */
    public function destroy(MalnutritionChildVisit $malnutritionChildVisit)
    {
        //
    }
}
