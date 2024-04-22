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
        $employee->role = $role->name;
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

            if($employee->active == false){
                return $this->error($employee ,'Employee account already frozen .' );

                }

            $employee->update([
                'active' => false,
            ]);

            return $this->success($employee ,'Employee account frozen successfully.' );
        } catch (ModelNotFoundException $e) {
            return $this->notFound($employee ,'Employee not found');
        }
    }

    public function unFreezeEmployee(Request $request)
    {
        $employeeId = $request->input('id');

        if (!$employeeId) {
            return response()->json(['error' => 'Missing employee ID'], 400);
        }

        try {

            $employee = Employee::findOrFail($employeeId);

            if($employee->active == true){
            return $this->error($employee ,'Employee account already unfrozen .' );

            }

            $employee->update([
                'active' => true,
            ]);

            return $this->success($employee ,'Employee account unfrozen successfully.' );
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
        if (!$employee->active) {
            return $this->error($employee , 'Employee account is freeze');
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
    if (!$employee->active) {
        return $this->error($employee , 'Employee account is freeze');
    }

    if (!Hash::check($request->input('password'), $employee->password)) {
        return $this->unauthorized($request->input('password'), 'Invalid password');
    }

    $role = $employee->getRoles();
    $token = $employee->createToken($role[0]);

    return $this->success([
        'token' => $token->plainTextToken,
        'employee'=>$employee,
        'role' => $role,
    ]);
}

//////////////////////////////////////////
public function getEmployeeDetails($id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return $this->notFound('Employee not found');
    }

    $contractStatus = 'Expired';
    $accountStatus = $employee->active ? 'Active' : 'Frozen';

    $isLoggedIn = false;


    $medicalCenterIds = $employee->employeeChoises()->pluck('medical_center_id');
    $currentWorkCenter = $medicalCenterIds->last();


    $latestContract = $employee->contracts()->latest()->first();
    if ($latestContract) {
        $expirationDate = Carbon::parse($latestContract->expiration_date);
        if (!$expirationDate->isPast()) {
            $contractStatus = 'Active';
        }
    }


    $responseData = [
        'id' => $employee->id,
        'contract_status' => $contractStatus,
        'account_status' => $accountStatus,
        'is_logged_in' => $isLoggedIn,
        'current_work_center' => $currentWorkCenter,
    ];

    return $this->success($responseData);
}
//////////////////////////////////

public function getEmployeeProfile(Request $request, $id)
{
    $employee = Employee::find($id);

    if (!$employee) {
        return $this->notFound('Employee not found');
    }



    $latestContract = $employee->contracts()->latest()->first();
    $responseData = [
        'id' => $employee->id,
        'name' => $employee->name,
        'phone_number' => $employee->phone_number,
        'user_name' => $employee->user_name,
        'address' => [
            'name' => $employee->addresses()->first()?->name,
            'subdistrict_id' => $employee->addresses()->first()?->subdistrict_id,
        ],
        'contract' => [
            'expiration_date' => $latestContract?->expiration_date,
            'contract_value' => $latestContract?->contract_value,
            'certificate' => $latestContract?->certificate,
            'medical_center_id' => $latestContract?->medical_center_id,

        ],

        'roles' => $employee->roles->pluck('name'),
    ];

    return $this->success($responseData);
}
////////////////////////////////
public function getWomenNutritionists()
{
    $womenNutritionists = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'women-nutritionist');
    })->get();

    if (!$womenNutritionists->count()) {
        return $this->notFound('No women nutritionists found');
    }

    return $this->success($womenNutritionists);
}
public function getWomenDoctors()
{
    $womenDoctors = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'women-doctor');
    })->get();

    if (!$womenDoctors->count()) {
        return $this->notFound('No women doctors found');
    }

    return $this->success($womenDoctors);
}
public function getChildDoctors()
{
    $childDoctors = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'child-doctor');
    })->get();

    if (!$childDoctors->count()) {
        return $this->notFound('No child doctors found');
    }

    return $this->success($childDoctors);
}

public function getReceptionists()
{
    $receptionists = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'receptionist');
    })->get();

    if (!$receptionists->count()) {
        return $this->notFound('No receptionists found');
    }

    return $this->success($receptionists);
}
public function getPharmacists()
{
    $pharmacists = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'pharmacist');
    })->get();

    if (!$pharmacists->count()) {
        return $this->notFound('No pharmacists found');
    }

    return $this->success($pharmacists);
}
public function getStatisticsEmployees()
{
    $statisticsEmployees = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'statistics-employee');
    })->get();

    if (!$statisticsEmployees->count()) {
        return $this->notFound('No statistics employees found');
    }

    return $this->success($statisticsEmployees);
}
public function getHealthEducationEmployees()
{
    $healthEducationEmployees = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'health-education');
    })->get();

    if (!$healthEducationEmployees->count()) {
        return $this->notFound('No health education employees found');
    }

    return $this->success($healthEducationEmployees);
}
public function getChildNutritionists()
{
    $childNutritionists = Employee::whereHas('roles', function ($query) {
        $query->where('name', 'child-nutritionist');
    })->get();

    if (!$childNutritionists->count()) {
        return $this->notFound('No child nutritionists found');
    }

    return $this->success($childNutritionists);
}

////////////////////////////////////////////







}
