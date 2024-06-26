<?php

namespace App\Repositories;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AccountRepositoryInterface;

class AccountRepository implements AccountRepositoryInterface 
{
    public function createAccount(Request $request){
        $account= Account::create([
            'type' => $request->input('type'),
            'user_name' => $request->input('user_name' ),
            'password' => Hash::make($request->input('password'))
        ]);
        return $account;
 
    }
 
    
}
