<?php

namespace App\Http\Controllers;

use App\Models\DoctorVisit;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Models\EmployeeChoise;
use App\Models\MedicalRecord;


class DoctorVisitController extends Controller
{
    use ApiResponseTrait;

    public function createDoctorVisit(Request $request){

        $validator = Validator::make($request->all(), [
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'result' => ['required', 'string'],
            'date' => ['required', 'date'],
            'activity'=>['required', 'in:health education,healthcare'],

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
            'activity'=>$request->input('activity')
        ]);

        return $this->created($DoctorVisit);

    }
}
