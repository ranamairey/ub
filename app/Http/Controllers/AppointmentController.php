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

        if($employee->isA('women-doctor') || $employee->isA('child-doctor')){
            $type = "doctor";
        }
        else if($employee->isA('women-nutritionist') || ($employee->isA('child-nutritionist'))|| ($employee->isA('nutritionist'))){
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
            $medicalRecord = $appointment->medicalRecord;
            $status = 'normal';
            if($medicalRecord->category == 'child'){
            $childTreatmentPrograms = $medicalRecord->childTreatmentPrograms;
                if($childTreatmentPrograms){
                $filteredChildTreatmentPrograms = $childTreatmentPrograms->filter(function ($program) {
                return is_null($program->end_cause) && is_null($program->end_date);
                });
                if(!$filteredChildTreatmentPrograms->isEmpty()){
                $status = $filteredChildTreatmentPrograms->first()->program_type;
                }
                }
            }

            if($medicalRecord->category == 'pregnant'){
            $womenTreatmentPrograms = $medicalRecord->womenTreatmentPrograms;
                if($womenTreatmentPrograms){
                $filteredWomenTreatmentPrograms = $womenTreatmentPrograms->filter(function ($program) {
                return is_null($program->end_cause) && is_null($program->end_date);
                });
                if(!$filteredWomenTreatmentPrograms->isEmpty()){
                $status = 'tsfp';
                }
                }
            }
            $birthDate = Carbon::parse($appointment->medicalRecord->birth_date);
            $age =$birthDate->age;
            $fullName =$appointment->medicalRecord->name . " "  .$appointment->medicalRecord->father_name . " " . $appointment->medicalRecord->last_name;
            $gender =$appointment->medicalRecord->gender;
            $appointment->age = $age;
            $appointment->fullName = $fullName;
            $appointment->gender = $gender;
            $appointment->status = $status;
        }


        return $this->success($appointments);

    }
    public function index()
    {
        $appointments = Appointment::all();

        foreach ($appointments as $appointment) {
            $birthDate = Carbon::parse($appointment->medicalRecord->birth_date);
            $age = $birthDate->age;
            $fullName = $appointment->medicalRecord->name . " " . $appointment->medicalRecord->father_name . " " . $appointment->medicalRecord->last_name;
            $gender = $appointment->medicalRecord->gender;
            $appointment->age = $age;
            $appointment->fullName = $fullName;
            $appointment->gender = $gender;
        }

        return $this->success($appointments);
    }
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
