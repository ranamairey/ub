<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;



#[\App\Aspects\transaction]
#[\App\Aspects\Logger]
class MedicalRecordController extends Controller
{
    use ApiResponseTrait;


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'required|string|min:10|max:20',
            'residence_status' => 'required|in:Resident,Immigrant,Returnee',
            'special_needs' => 'required|boolean',
            'birth_date' => 'required|date',
            'related_person' => 'nullable|string|max:255',
            'related_person_phone_number' => 'nullable|string|min:10|max:20',
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $validatedData = $validator->validated();
        $employee = auth('sanctum')->user();

        if ($validatedData['category'] === 'pregnant' && $validatedData['gender'] === 'Male') {
            return $this->error(null, 'A male gender cannot be assigned to a pregnant patient.');
        }




        $existMedicalRecord = MedicalRecord::where([
            ['name', $request->input('name')],
            ['mother_name', $request->input('mother_name')],
            ['father_name', $request->input('father_name')]
        ])->first();

        if ($existMedicalRecord) {
            return $this->error(null, 'المريض له سجل طبي');
        }

        $medicalRecord = new MedicalRecord($validatedData);
        $medicalRecord->employee()->associate($employee);
        $medicalRecord->save();

        $addressData = $request->get('address');

        $address = $medicalRecord->addresses()->create([
            'name' => $addressData['name'],
            'subdistrict_id' => $addressData['subdistrict_id'],
        ]);

        return $this->created($medicalRecord, 'Medical record created successfully!');
    }
    public function update(Request $request, $id)
    {
        $medicalRecord = MedicalRecord::findOrFail($id);

        if (!$medicalRecord) {
            return $this->notFound('السجل غير موجود');
        }

        $validator = Validator::make($request->all(), [
            'phone_number' => 'sometimes|required|string|min:10|max:20',
            'residence_status' => 'sometimes|required|in:Resident,Immigrant,Returnee',
            'related_person_phone_number' => 'sometimes|required|string|min:10|max:20',
            'address.subdistrict_id' => ['sometimes','required', 'exists:subdistricts,id'],
            'address.name' => ['sometimes','required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $validatedData = $validator->validated();

        $medicalRecord->update($validatedData);

        $addressData = $request->get('addresses');

        if ($addressData) {
            $existingAddress = $medicalRecord->addresses()->first();

            if ($existingAddress) {
                $existingAddress->update($addressData);
            } else {
                $medicalRecord->addresses()->create($addressData);
            }
        }

        $responseData = $medicalRecord->fresh()->toArray();
        $address = $medicalRecord->addresses()->first();

        $responseData['addresses'] = $address ? $address->fresh()->toArray() : null;

        return $this->success($responseData, 'Medical record updated successfully!');
    }

    public function show(Request $request, $id)
    {
        $medicalRecord = MedicalRecord::with('addresses')->find($request->route('id')); // Access ID from route

        if (!$medicalRecord) {
            return $this->notFound('Medical record not found');
        }
        return $this->success($medicalRecord, 'Medical record retrieved successfully!');

    }
    public function getAllVisitsByRecordId(Request $request, $id)
    {
      $medicalRecord = MedicalRecord::find($id);

      if (!$medicalRecord) {
        return $this->notFound('Medical record not found');
      }


      $isChild = $medicalRecord->category === 'child';
      $isWoman = $medicalRecord->category === 'pregnant';

      if ($isChild) {

        $childVisits = $medicalRecord->routineChildVisits()->get();


        $data = [
          'medical_record' => $medicalRecord->toArray(),
          'visits' => $childVisits->toArray(),
        ];

        return $this->success($data, 'Child visits retrieved successfully!');
      } else if ($isWoman) {

        $womenVisits = $medicalRecord->routineWomenVisits()->get();


        $data = [
          'medical_record' => $medicalRecord->toArray(),
          'visits' => $womenVisits->toArray(),
        ];

        return $this->success($data, 'Women visits retrieved successfully!');
      } else {

        return $this->notFound('ther is no visits');
      }
    }

    //  Also for search
    public function getRecordDetails($id)
    {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return $this->notFound($id, "The medical record with the given ID was not found.");
        }

        $fullName = $medicalRecord->name . " " . $medicalRecord->father_name . " " . $medicalRecord->last_name;
        $birthDate = Carbon::parse($medicalRecord->birth_date);
        $age = $birthDate->age;

        $ageDescription = ($age < 12) ? "$age years" : "$age months";

        $medicalRecord->full_name = $fullName;
        $medicalRecord->age = $ageDescription;
        $medicalRecord->address_name = $medicalRecord->addresses()->latest('created_at')->first()->name;

        return $this->success($medicalRecord);
    }



        public function showMyRecord()
        {
            $loggedInUser = auth('sanctum')->user();

            $linkedAccount = $loggedInUser->account;

            if (!$linkedAccount) {
                return $this->error(null, 'User is not linked to any medical record');
            }

            // Check for at least one linked record
            if ($linkedAccount->medicalRecords->count() > 0) {
                $medicalRecord = $linkedAccount->medicalRecords->first();
                return $this->success($medicalRecord, 'Medical record retrieved successfully!');
            } else {
                return $this->notFound('User has no medical records');
            }
        }

        public function getCompletedTreatmentsByRecordId(Request $request, $id)
        {
            $medicalRecord = MedicalRecord::find($id);

            if (!$medicalRecord) {
                return $this->notFound('Medical record not found');
            }

            $isChild = $medicalRecord->category === 'child';
            $isWoman = $medicalRecord->category === 'pregnant';

            $completedTreatments = null;

            if ($isChild) {
                $completedTreatments = $medicalRecord->childTreatmentPrograms()->whereNotNull('end_date')->get();
            } elseif ($isWoman) {
                $completedTreatments = $medicalRecord->womenTreatmentPrograms()->whereNotNull('end_date')->get();
            }

            if ($completedTreatments->isEmpty()) {
                return $this->notFound('No completed treatment programs found for this medical record.');
            }

            $data = [
                'medical_record' => $medicalRecord->toArray(),
                'treatments' => [],
            ];

            foreach ($completedTreatments as $treatment) {
                $visits = $treatment->{$isChild ? 'malnutritionChildVisits' : 'malnutritionWomenVisits'}()->get();
                $data['treatments'][] = [
                    'treatment' => $treatment->toArray(),
                    'visits' => $visits->toArray(),
                ];
            }

            return $this->success($data, 'Completed treatment programs and visits retrieved successfully!');
        }


        public function search(Request $request)
        {
            $input = $request->input('input');

            $query = MedicalRecord::with('addresses');

            if (is_numeric($input)) {
                $medicalRecords = $query->where('id', $input)->get();
            } else {
                $medicalRecords = $query->where(function ($q) use ($input) {
                    $q->where('name', 'LIKE', '%' . $input . '%')
                      ->orWhere('father_name', 'LIKE', '%' . $input . '%')
                      ->orWhere('last_name', 'LIKE', '%' . $input . '%');
                })->get();
            }

            if ($medicalRecords->isEmpty()) {
                return $this->notFound('السجل الطبي غير موجود');
            }

            $results = [];
            foreach ($medicalRecords as $medicalRecord) {
                $fullName = $medicalRecord->name . " " . $medicalRecord->father_name . " " . $medicalRecord->last_name;
                $birthDate = Carbon::parse($medicalRecord->birth_date);
                $ageInYears = $birthDate->age;
                $ageInMonths = $birthDate->diffInMonths(Carbon::now());

                $ageDescription = ($ageInMonths < 12) ? "$ageInMonths months" : "$ageInYears years";

                $medicalRecord->full_name = $fullName;
                $medicalRecord->age = $ageDescription;
                $medicalRecord->address_name = $medicalRecord->addresses()->latest('created_at')->first()->name;
                $results[] = $medicalRecord;
            }

            return $this->success($results, 'Medical records retrieved successfully!');
        }

}
