<?php

namespace App\Http\Controllers;

use Bouncer;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => ['required', 'string', 'max:255','unique:employees,user_name'],
            'password' => ['required', 'string', 'min:8'],
            'employee_choise.medical_center_id' => ['required', 'exists:medical_centers,id'],
            'employee_choise.coverage_id' => ['required', 'exists:coverages,id'],
            'employee_choise.office_id' => ['required', 'exists:offices,id'],
            'employee_choise.activity_id' => ['required', 'exists:activities,id'],
            'employee_choise.agency_id' => ['required', 'exists:agencies,id'],
            'employee_choise.access_id' => ['required', 'exists:accesses,id'],
            'employee_choise.partner_id' => ['required', 'exists:partners,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $employee = Employee::where('user_name' , $request->input('user_name'))->first();

        
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if (!Hash::check($request->input('password'), $employee->password)) {
            return response()->json(['message' => 'Invalid password'], 401);
        }

        $employeeChoise = EmployeeChoise::create([
            'medical_center_id' => $request['employee_choise']['medical_center_id'],
            'coverage_id' => $request['employee_choise']['coverage_id'],
            'office_id' => $request['employee_choise']['office_id'],
            'activity_id' => $request['employee_choise']['activity_id'],
            'agency_id' => $request['employee_choise']['agency_id'],
            'access_id' => $request['employee_choise']['access_id'],
            'partner_id' => $request['employee_choise']['partner_id'],
        ]);
        // Role name in the token
        $token = $employee()->createToken('employee_token');
        
        return response()->json(['token' => $token->plainTextToken, 'employee choise' => $employeeChoise] , 200);

    }

}
