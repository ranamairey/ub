<?php

namespace App\Http\Controllers;

use App\Models\Advice;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
use App\Interfaces\AccountRepositoryInterface;


class AccountController extends Controller
{
    use ApiResponseTrait;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository) 
    {
        $this->accountRepository = $accountRepository;
    }

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

        $account = $this->accountRepository->createAccount($request);
        
        // = Account::create([
        //     'type' => $request->input('type'),
        //     'user_name' => $request->input('user_name' ),
        //     'password' => Hash::make($request->input('password'))
        // ]);

        return $this->created($account);



    }

    public function linkAccountToRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'medical_record_id' => 'required|exists:medical_records,id',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $medicalRecordId = $request->input('medical_record_id');
        $accountId = $request->input('account_id');

        $medicalRecord = MedicalRecord::find($medicalRecordId);
        $account = Account::find($accountId);

        if (!$medicalRecord) {
            return $this->notFound($medicalRecordId, "السجل الطبي غير موجود.");
        }

        if ($medicalRecord->account_id != null) {
            return $this->unprocessable($medicalRecord, "هذا السجل الطبي يملك حساباً بالفعل.");
        }

        if (!$account) {
            return $this->notFound($accountId, "الحساب غير موجود.");
        }

        $medicalRecord->account()->associate($account);
        $medicalRecord->save();

        // Retrieve all linked medical records for the account
        $linkedRecords = $account->medicalRecords;

        $responseData = [
            'account_id' => $account->id,
            'linked_records' => $linkedRecords,
        ];

        return $this->success($responseData, "تم ربط الحساب بالسجل بنجاح.");
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

        $user = Account::where('user_name', $request->input('user_name'))->first();

        if (!$user) {
            return $this->notFound($user, 'User not found');
        }
        if (!Hash::check($request->input('password'), $user->password)) {
            return $this->unauthorized($request->input('password'), 'كلمة سر غير صالحة.');
        }

        $token = $user->createToken('userToken');

        $responseData = [
            'account_id' => $user->id,
            'token' => $token->plainTextToken,
        ];

        return $this->success($responseData);
    }

    public function showLinkedRecords(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $accountId = $request->input('account_id');
        $account = Account::find($accountId);

        if (!$account) {
            return $this->notFound($accountId, "الحساب غير موجود.");
        }

        $linkedRecords = $account->medicalRecords;

        if ($linkedRecords->count() > 0) {
            $responseData = [
                'account_id' => $account->id,
                'linked_records' => $linkedRecords,
            ];
            return $this->success($responseData);
        } else {
            return $this->notFound('لا يوجد سجلات طبية مرتبطة بهذا الحساب.');
        }
    }

        
    public function showLinkedAdvices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
        ]);
        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }
        $accountId = $request->input('account_id');
        $account = Account::find($accountId);
        if (!$account) {
            return $this->notFound($accountId, "الحساب غير موجود.");
        }
        // Eager load linked medical records
        $account->load(['medicalRecords']);
        $linkedRecords = $account->medicalRecords;
        if ($linkedRecords->isEmpty()) {
            return $this->notFound('لا يوجد سجلات طبية مرتبطة بهذا الحساب.');
        }
        $patientTypes = [];
        $allAdvices = [];
        foreach ($linkedRecords as $record) {
            if (in_array($record->category, ['child', 'pregnant'])) {
                $patientTypes[] = $record->category;
                $filteredAdvices = Advice::where(function ($query) use ($record) {
                    $query->where('target_group', 'like', "%$record->category%")
                          ->orWhere('target_group', 'like', "%both%");
                })->get();
                $allAdvices = array_merge($allAdvices, $filteredAdvices->toArray());
            }
        }
        $patientTypes = array_unique($patientTypes); // Remove duplicates
        $responseData = [
            'account_id' => $account->id,
            'medical_records' => $linkedRecords->toArray(),
            'patient_types' => $patientTypes,
            'all_advices' => $allAdvices,
        ];
        return $this->success($responseData);
        }

    }
