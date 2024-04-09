<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
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
            'employee_id' => 'required|integer|exists:employees,id',
            'address.governorate_id' => ['required', 'exists:governorates,id'],
            'address.district_id' => ['required', 'exists:districts,id'],
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $validatedData = $validator->validated();

        $employee = Employee::find($request->employee_id);

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
}
