<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Bouncer;

class EmployeeController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255','unique:employees,phone_number'],
            'user_name' => ['required', 'string', 'max:255','unique:employees,user_name'],
            'password' => ['required', 'string', 'min:8'],
            'address.governorate_id' => ['required', 'exists:governorates,id'],
            'address.district_id' => ['required', 'exists:districts,id'],
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
            'contract.expiration_date' => ['required', 'date'],
            'contract.contract_value' => ['required', 'integer'],
            'contract.certificate' => ['required', 'string'],
            'contract.medical_center_id' => ['required', 'integer', 'exists:medical_centers,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $employee = Employee::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'user_name' => $request->user_name,
            'password' => Hash::make($request->password),
            'active' => true,
        ]);

        $addressData = $request->get('address');

        $address = $employee->addresses()->create([
            'name' => $addressData['name'],
            'subdistrict_id' => $addressData['subdistrict_id'],
        ]);

        $contract = $employee->contracts()->create([
            'expiration_date' => $request['contract']['expiration_date'],
            'contract_value' => $request['contract']['contract_value'],
            'certificate' => $request['contract']['certificate'],
            'medical_center_id' => $request['contract']['medical_center_id'],
        ]);

        $roleName = $request->input('role');

        if ($roleName) {
            $role = Bouncer::role()->where('name', $roleName)->first();
            if ($role) {
                $employee->assign($role);
            } else {
            return response()->json('Bad Request', 400);
            }
        }

        return response()->json($employee, 201);
    }

    public function freezeEmployee(Request $request)
    {
        $employeeId = $request->input('id');

        if (!$employeeId) {
            return response()->json(['error' => 'Missing employee ID'], 400);
        }

        try {

            $employee = Employee::findOrFail($employeeId);

            $employee->update([
                'active' => false,
            ]);

            return response()->json([
                'message' => 'Employee account frozen successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json(['error' => 'Employee not found'], 404);
        }
    }


}
