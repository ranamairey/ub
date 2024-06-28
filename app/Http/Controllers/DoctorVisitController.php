<?php

namespace App\Http\Controllers;

use App\Models\DoctorVisit;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\DoctorVisitRepositoryInterface;


class DoctorVisitController extends Controller
{
    use ApiResponseTrait;
    private DoctorVisitRepositoryInterface $doctorVisitRepository;

    public function __construct(DoctorVisitRepositoryInterface $doctorVisitRepository) 
    {
        $this->doctorVisitRepository = $doctorVisitRepository;
    }

    public function createDoctorVisit(Request $request){

        $validator = Validator::make($request->all(), [
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'result' => ['required', 'string'],
            'health_education' => ['required', 'boolean'],
            'health_care' => ['required', 'boolean'],
            ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $medicalRecord = MedicalRecord::where('id', $request->input('medical_record_id'))->first();
        if (!$medicalRecord) {
            return $this->unprocessable($DoctorVisit , 'The specified medical record does not exist.');
        }

       

        $employee = auth('sanctum')->user();

        if($employee->isA('women-doctor') && $medicalRecord->category == "child" || $employee->isA('child-doctor') && $medicalRecord->category == "pregnant"){
            return $this->error($medicalRecord->id , "اختصاص الطبيب لا يتوافق مع نوع سجل المريض");
        }


        $request->employee_id = $employee->id;
        $request->employee_choise = $employee->employeeChoises()->latest('created_at')->first()->id;
        $doctorVisit = $this->doctorVisitRepository->createDoctorVisit($request);

        // $DoctorVisit = DoctorVisit::create([
        //     'employee_id' => $employee->id,
        //     'employee_choise_id' => $employee->employeeChoises()->latest('created_at')->first()->id,
        //     'medical_record_id' => $request->input('medical_record_id'),
        //     'result' => $request->input('result'),
        //     'date' => $request->input('date'),
        //     'health_education' => $requ->est->input('health_education'),
        //     'health_care' => $request->input('health_care'),
        // ]);

        return $this->created($doctorVisit);
    }
    
    public function getDoctorVisitsByMedicalRecordId(Request $request, $medicalRecordId)
    {
        $validator = Validator::make(['medical_record_id' => $medicalRecordId], [
            'medical_record_id' => 'required|integer|exists:medical_records,id',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $visits = DoctorVisit::where('medical_record_id', $medicalRecordId)->get();

        if (!$visits->count()) {
            return $this->notFound('لا يوجد زيارات للطبيب من أجل السجل الطبي المعطى.');
        }
        $activity =null;
        foreach ($visits as $visit) {
            $health_care = $visit->health_care;
            $health_education = $visit->health_education;
            if(!$health_care){
                $activity = "تثقيف صحي" ;
            }
            if(!$health_education){
                $activity  = "رعاية صحية" ;
            }
           $visit->activity =  $activity;
        }
        return $this->success($visits);
        
    }
}
