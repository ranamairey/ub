<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponseTrait;
use App\Models\RoutineWomenVisit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoutineWomenVisitController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($record)
    {
        $visits = RoutineWomenVisit::where('medical_record_id', $record)->get();

        if (!$visits->count()) {
            return $this->notFound('No visits found for Record ID: ' . $record);
        }

        return $this->success($visits);
    }


    public function createWomenVisit(Request $request){

        $validator = Validator::make($request->all(), [
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'z_score' => ['required', 'integer'],
            'current_status' => [
                'required',
                Rule::in(['mam','normal']),
            ],
            'status_type' => [
                'required',
                Rule::in(['pregnant','lactating','non']),
            ],
            'date' => ['required', 'date'], 'IYCF' => ['required', 'boolean'],
            'nutritional_survey' => ['required', 'boolean'],
            'micronutrients' => ['required', 'boolean'],
            'high_energy_biscuits' => ['required', 'boolean'],
            'health_education' => ['required', 'boolean'],
            'weight' => ['required','numeric'],
            'height' => ['required','numeric'],

            ]);


        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }


        if (! MedicalRecord::where('id', $request->input('medical_record_id'))->exists()) {
            return $this->unprocessable($routineWomenVisit , 'The specified medical record does not exist.');
        }


        $employee = auth('sanctum')->user();
$employee_id = auth('sanctum')->user()->id;
        $routineWomenVisit = RoutineWomenVisit::create([
            'employee_id' => $employee->id,
            'employee_choise_id' => EmployeeChoise::where('employee_id', $employee_id)->latest('created_at')->first()->id,
            'medical_record_id' => $request->input('medical_record_id'),
            'current_status' =>  $request->input('current_status'),
            'status_type'  =>   $request->input('status_type'),
            'z_score' =>  $request->input('z_score'),
            'date' =>   $request->input('date'),
            'IYCF' => $request->input('IYCF'),
            'nutritional_survey' => $request->input('nutritional_survey'),
            'micronutrients' => $request->input('micronutrients'),
            'high_energy_biscuits' => $request->input('high_energy_biscuits'),
            'health_education' => $request->input('health_education'),
            'weight' => $request->input('weight'),
            'height' => $request->input('height'),

        ]);
        return $this->created($routineWomenVisit);



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
     * @param  \App\Models\RoutineWomenVisit  $routineWomenVisit
     * @return \Illuminate\Http\Response
     */
    public function show(RoutineWomenVisit $routineWomenVisit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RoutineWomenVisit  $routineWomenVisit
     * @return \Illuminate\Http\Response
     */
    public function edit(RoutineWomenVisit $routineWomenVisit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoutineWomenVisit  $routineWomenVisit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoutineWomenVisit $routineWomenVisit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoutineWomenVisit  $routineWomenVisit
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoutineWomenVisit $routineWomenVisit)
    {
        //
    }
}
