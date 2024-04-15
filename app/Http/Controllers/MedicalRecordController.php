<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;


class MedicalRecordController extends Controller
{
    use ApiResponseTrait;


    /**
     * Store a newly created medical record in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'required|string|min:10|max:20',
            'residence_status' => 'required|in:Resident,Immigrant,Returnee',
            'special_needs' => 'required|boolean',
            'related_person' => 'nullable|string|max:255',
            'related_person_phone_number' => 'nullable|string|min:10|max:20',
            'address.governorate_id' => ['required', 'exists:governorates,id'],
            'address.district_id' => ['required', 'exists:districts,id'],
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $validatedData = $validator->validated();

        $employee = auth('sanctum')->user();

        $existMedicalRecord = MedicalRecord::where([
            ['name' , $request->input('name')],
            ['mother_name' , $request->input('mother_name')],
            ['father_name' , $request->input('father_name')]
        ])->first();

        if($existMedicalRecord){
            return response()->json(['error'  => 'This patient already has a medical record'], 409);
        }

        $medicalRecord = new MedicalRecord($validatedData);
        $medicalRecord->employee()->associate($employee);
        $medicalRecord->save();

        $recordId = $medicalRecord->id;

        $addressData = $request->get('address');

        $address = $medicalRecord->addresses()->create([
            'name' => $addressData['name'],
            'subdistrict_id' => $addressData['subdistrict_id'],
        ]);


        $recordId = $medicalRecord->id;

        return response()->json([
            'message' => 'Medical record created successfully!',
            'id' => $recordId,
            'data' => $validatedData,

        ], 201);
    }
    public function update(Request $request, $id)
    {
            $validator = Validator::make($request->all(), [
                'category' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female',
                'phone_number' => 'required|string|min:10|max:20',
                'residence_status' => 'required|in:Resident,Immigrant,Returnee',
                'special_needs' => 'required|boolean',
                'related_person' => 'nullable|string|max:255',
                'related_person_phone_number' => 'nullable|string|min:10|max:20',
                'address.governorate_id' => ['required', 'exists:governorates,id'],
                'address.district_id' => ['required', 'exists:districts,id'],
                'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
                'address.name' => ['required', 'string', 'max:255'],
            ]);


        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $validatedData = $validator->validated();

        $medicalRecord = MedicalRecord::findOrFail($id);

        if (!$medicalRecord) {
            return $this->notFound('Medical record not found');
        }

        $addressData = $request->get('address');

        $medicalRecord->update($validatedData);

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

        $responseData['address'] = $address ? $address->fresh()->toArray() : null;

        return $this->success($responseData, 'Medical record updated successfully!');
    }
}

