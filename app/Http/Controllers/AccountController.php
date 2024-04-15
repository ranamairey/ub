<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;


class AccountController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'type' => [
                'required',
                Rule::in(['Patient', 'Related']),
            ],
            'user_name' => 'required|max:255|unique:accounts',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $account= Account::create([
            'type' => $request->input('type'),
            'user_name' => $request->input('user_name' ),
            'password' => Hash::make($request->input('password'))
        ]);

        return $this->created($account);



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function linkAccountToRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required', 'exists:accounts,id',
            'medical_record_id' => 'required', 'exists:medical_records,id',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }
        $medicalRecordId =$request->input('medical_record_id');
        $accountId = $request->input('account_id');
        $medicalRecord = MedicalRecord::find($medicalRecordId);
        $account = Account::find($accountId);

        if(! $medicalRecord){
            return $this->notFound($medicalRecordId , "Medical record not found");
        }
        if($medicalRecord->account_id != null){
            return $this->unprocessable($medicalRecord ,"The medical record already has an account.");
        }
        if(! $account){
            return $this->notFound($accountId , "Account not found");
        }

        $medicalRecord->account()->associate($account);
        $medicalRecord->save();

        return $this->success($medicalRecord);
    }

    public function login(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'user_name' => 'required|string',
        'password' => 'required|string',
    ]);

    if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
    }

    $user = Account::where('user_name' , $request->input('user_name'))->first();

    if (!$user) {
        return $this->notFound($user , 'user not found');
    }
    if (!Hash::check($request->input('password'), $user->password)) {
        return $this->unauthorized($request->input('password') , 'Invalid password');
    }

    $token = $user->createToken('userToken');

    return $this->success($token->plainTextToken);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        //
    }
}
