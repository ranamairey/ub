<?php

namespace App\Http\Controllers;

use App\Models\DoctorVisit;
use Illuminate\Http\Request;

class DoctorVisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\DoctorVisit  $doctorVisit
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorVisit $doctorVisit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DoctorVisit  $doctorVisit
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorVisit $doctorVisit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorVisit  $doctorVisit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DoctorVisit $doctorVisit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DoctorVisit  $doctorVisit
     * @return \Illuminate\Http\Response
     */
    public function destroy(DoctorVisit $doctorVisit)
    {
        //
    }

    public function createDoctorVisit(Request $request){

        $validator = Validator::make($request->all(), [
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'result' => ['required', 'string'],
            'date' => ['required', 'date'],

            ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }


        if (! MedicalRecord::where('id', $request->input('medical_record_id'))->exists()) {
            return $this->unprocessable($DoctorVisit , 'The specified medical record does not exist.');
        }

        $employee = auth('sanctum')->user();

        $DoctorVisit = DoctorVisit::create([
            'employee_id' => $employee->id,
            'employee_choise_id' => $employee->employeeChoises()->first()->id,
            'medical_record_id' => $request->input('medical_record_id'),
            'medical_record_id' => $request->input('medical_record_id'),
            'result' => $request->input('result'),
            'date' => $request->input('date'),
        ]);

        return $this->created($DoctorVisit);

    }
}
