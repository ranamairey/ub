<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class AppointmentController extends Controller
{
    use ApiResponseTrait;

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
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }
        $employeeId = $request->input('employee_id');
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return $this->notFound($employee , 'Employee not found');
        }
        $medicalRecordId = $request->input('medical_record_id');

        $medicalRecord = MedicalRecord::find($medicalRecordId );

        if (!$medicalRecord) {
            return $this->notFound('Medical record not found');
        }

        if($employee->isA('doctor')){
            $type = "Doctor";
        }
        else if($employee->isA('nutritionist')){
            $type = "Nutritionist";
        }
        else{
            return $this->error($employeeId , "Error in employee type");
        }
        $receptionistId = auth('sanctum')->user()->id;

        $appointment = Appointment::create([
            'employee_id' => $employeeId ,
            'receptionist_id' => $receptionistId,
            'medical_record_id' => $medicalRecordId ,
            'type' => $type
        ]);

        return $this->success($appointment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(){
    
        $employee = auth('sanctum')->user();
        if (!$employee) {
            return $this->notFound($employee , 'Employee not found');
        }
        

        $appointments = Appointment::where('employee_id' , $employee->id)->get();

        foreach ($appointments as $appointment) {
           $status = 5;
            $birthDate = Carbon::parse($appointment->medicalRecord->birth_date);
            $age =$birthDate->age;
            $fullName =$appointment->medicalRecord->name . " "  .$appointment->medicalRecord->father_name . " " . $appointment->medicalRecord->last_name;
            $gender =$appointment->medicalRecord->gender;
            $appointment->age = $age;
            $appointment->fullName = $fullName;
            $appointment->gender = $gender;   
            $appointment->expectedNextVisit = null;
        } 
        
    
        return $this->success($appointments);

    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if(! $appointment){
            return $this->notFound($id);
        }
        $appointment->delete();
        return $this->success($appointment);

    }
}
