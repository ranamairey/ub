<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\Appointment;
use Silber\Bouncer\Bouncer;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            //
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        // $employeeId = $request->input('employee_id');


        $type = $request->input('type');
        $employee = $this->malak($type);

        try{
            $employee = Employee::find($employee->id);
            if (!$employee) {
                return $this->notFound($employee, 'Employee not found');
            }
        }catch(\Exception $ex ){
            return  $this->notFound($ex) ;
        }



        $medicalRecordId = $request->input('medical_record_id');
        $medicalRecord = MedicalRecord::find($medicalRecordId);

        if (!$medicalRecord) {
            return $this->notFound('Medical record not found');
        }

        if ($type == "child-nutritionist" || $type == "women-nutritionist")
        {
            $employeeType = 'Nutritionist';
        }
        else if($type == "child-doctor" || $type == "women-doctor"){
            $employeeType = 'Doctor';
        }


        // if (($employee->isA('women-doctor') && $medicalRecord->category === 'pregnant') ||
        // ($employee->isA('child-doctor') && $medicalRecord->category === 'child')) {
        // $type = "doctor";
        // } else if (($employee->isA('women-nutritionist') && $medicalRecord->category === 'pregnant') ||
        //            ($employee->isA('child-nutritionist') && $medicalRecord->category === 'child')) {
        //     $type = "Nutritionist";
        // } else if (($employee->isA('women-nutritionist') || $employee->isA('nutritionist')) &&
        //            $medicalRecord->category !== 'child') {
        //     $type = "Nutritionist";
        // } else if ($employee->isA('child-doctor') && $medicalRecord->category !== 'child') {

        //     return $this->error($employeeId, "Employee is a child doctor but patient is not a child. Consider a different doctor.");
        // } else {
        //     return $this->error($employeeId, "Error in employee type");
        // }


        $receptionistId = auth('sanctum')->user()->id;

        $appointment = Appointment::create([
            'employee_id' => $employee->id,
            'receptionist_id' => $receptionistId,
            'medical_record_id' => $medicalRecordId,
            'employee_type' => $employeeType
        ]);

        return $this->success($appointment);
    }



    public function malak($type){
        $authEmployee = auth('sanctum')->user();
        $employeeChoice = EmployeeChoise::where('employee_id', $authEmployee->id)->latest('created_at')->first();
        $medicalCenterIdReciptionist = $employeeChoice->medical_center_id;
        $matchingEmployees = null;
        $bouncer = app(Bouncer::class);
        $employees = Employee::all();
        foreach ($employees as $loopEmployee) {
            $employeeChoice = EmployeeChoise::where('employee_id', $loopEmployee->id)->latest('created_at')->first();
            if ($employeeChoice && $employeeChoice->medical_center_id == $medicalCenterIdReciptionist ) {
                if( $bouncer->is($loopEmployee)->an($type) ){
                    $matchingEmployees = $loopEmployee;
                    return $matchingEmployees ;
                }
            }
            else if ($matchingEmployees == null){
                return $this->error($type , "لا يوجد موظف يوافق النوع المرسل");
            }
        }
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
