<?php

namespace App\Http\Controllers;

use Bouncer;
use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponseTrait;


class EmployeeController extends Controller
{
    use ApiResponseTrait;

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
            return $this->unprocessable($validator->errors());
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

            return $this->error($roleName);

            }
        }

        return $this->created($employee);

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

            return $this->success($employee ,'Employee account frozen successfully.' );
        } catch (ModelNotFoundException $e) {
            return $this->notFound($employee ,'Employee not found');
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'employee_choise.medical_center_id' => ['required', 'exists:medical_centers,id'],
            'employee_choise.coverage_id' => ['required', 'exists:coverages,id'],
            'employee_choise.office_id' => ['required', 'exists:offices,id'],
            'employee_choise.agency_id' => ['required', 'exists:agencies,id'],
            'employee_choise.access_id' => ['required', 'exists:accesses,id'],
            'employee_choise.partner_id' => ['required', 'exists:partners,id'],
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $employee = Employee::where('user_name' , $request->input('user_name'))->first();


        if (!$employee) {
            return $this->notFound($employee , 'Employee not found');
        }

        if (!Hash::check($request->input('password'), $employee->password)) {
            return $this->unauthorized($request->input('password') , 'Invalid password');
        }

        $employeeChoise = $employee->employeeChoises()->create([
            'medical_center_id' => $request['employee_choise']['medical_center_id'],
            'coverage_id' => $request['employee_choise']['coverage_id'],
            'office_id' => $request['employee_choise']['office_id'],
            'agency_id' => $request['employee_choise']['agency_id'],
            'access_id' => $request['employee_choise']['access_id'],
            'partner_id' => $request['employee_choise']['partner_id'],
        ]);
        $role = $employee->getRoles();
        $token = $employee->createToken($role[0]);
        return $this->success(['token' => $token->plainTextToken, 'employee choise' => $employeeChoise , 'role' => $role]);
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function renewalEmployeeContract(Request $request){
        $validator = Validator::make($request->all(), [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'expiration_date' => ['required', 'date'],
            'contract_value' => ['required', 'integer'],
            'certificate' => ['required', 'string'],
            'medical_center_id' => ['required', 'integer', 'exists:medical_centers,id'],
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $employeeId = $request->input('employee_id');
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return $this->notFound($employee , 'Employee not found');
        }

        $contract = $employee->contracts()->first();

        if (!$contract) {
            return $this->notFound($contract , 'Contract not found');
        }
        $expirationDate = Carbon::parse($contract->expiration_date);

        if( ! $expirationDate->isPast() ){
            return $this->error($contract , 'The contract did not expired yet');

        }

        $contract->delete();

        $newContract= $employee->contracts()->create([
            'expiration_date' => $request->input('expiration_date'),
            'contract_value' => $request->input('contract_value'),
            'certificate' => $request->input('certificate'),
            'medical_center_id' => $request->input('medical_center_id'),
        ]);
        return $this->created($contract);
    }

    public function updateEmployee(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',

        ]);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $employee = Employee::find($id);

        if (!$employee) {
            return $this->notFound($employee_id , 'Employee not found');
        }

        $employee->update($data);

        return $this->success($employee);
    }

    public function findEmployee($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return $this->notFound('Employee not found');
        }

        return $this->success($employee);;
    }
    ////////////////////////////////////////////////
    public function statisticsLogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_name' => ['required', 'string', 'max:255'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }

    $employee = Employee::where('user_name', $request->input('user_name'))->first();

    if (!$employee) {
        return $this->notFound($employee, 'Employee not found');
    }

    if (!Hash::check($request->input('password'), $employee->password)) {
        return $this->unauthorized($request->input('password'), 'Invalid password');
    }

    $role = $employee->getRoles();
    $token = $employee->createToken($role[0]);

    return $this->success([
        'token' => $token->plainTextToken,
        'role' => $role,
    ]);
}




}
