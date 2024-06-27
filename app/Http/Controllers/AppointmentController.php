<?php
namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Appointment;
use Silber\Bouncer\Bouncer;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AppointmentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;


#[\App\Aspects\transaction]
#[\App\Aspects\Logger]
class AppointmentController extends Controller
{
    use ApiResponseTrait;
    private AppointmentRepositoryInterface $appointmentRepository;

    public function __construct(AppointmentRepositoryInterface $appointmentRepository) 
    {
        $this->appointmentRepository = $appointmentRepository;
    }

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
        return $this->notFound($employee, ' الموظف غير موجود');
      }

      $medicalRecordId = $request->input('medical_record_id');
      $medicalRecord = MedicalRecord::find($medicalRecordId);

        $type = "";

        if (!$medicalRecord) {
          return $this->notFound('السجل غير موجود');
        }
       
        $medicalRecordId = $request->input('medical_record_id');
        $medicalRecord = MedicalRecord::find($medicalRecordId);

        if (!$medicalRecord) {
            return $this->notFound('السجل غير موجود');
        }

        if (($employee->isA('women-doctor') && $medicalRecord->category === 'pregnant') ||
        ($employee->isA('child-doctor') && $medicalRecord->category === 'child')) {
        $type = "doctor";
        } else if (($employee->isA('women-nutritionist') && $medicalRecord->category === 'pregnant') ||
                   ($employee->isA('child-nutritionist') && $medicalRecord->category === 'child')) {
            $type = "Nutritionist";
        } else if (($employee->isA('women-nutritionist') || $employee->isA('nutritionist')) &&
                   $medicalRecord->category !== 'child') {
            $type = "Nutritionist";
        } else if ($employee->isA('child-doctor') && $medicalRecord->category !== 'child') {

            return $this->error($employeeId, "اختصاص الطبيب والسجل لا يتوافقان");
        } else {
            return $this->error($employeeId, "خطأ في نوع الموظف");
        }

        $receptionistId = auth('sanctum')->user()->id;

        $request-> employee_id = $employee->id;
        $request->receptionist_id =$receptionistId;
        $request ->medical_record_id = $medicalRecordId;
        $request ->employee_type = $type;


        $appointment =$this->appointmentRepository->createAppointment($request);

        return $this->success($appointment);
    }

    public function show(){

        $employee = auth('sanctum')->user();
        if (!$employee) {
            return $this->notFound($employee , 'الموظف غير موجود');
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
