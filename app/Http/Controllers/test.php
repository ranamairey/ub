<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Bouncer;

class test extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send verification email (optional)

        /////////////////////////////////////اعطاء صلاحية

        Bouncer::assign('admin')->to($user);
        return response()->json([
            'message' => 'User created successfully!',
            'user' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        // Check login credentials and handle success
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (Bouncer::is($user)->an('admin')) {
            // تحقق من صلاحية

                $token = $user->createToken('my-app')->plainTextToken;

                return response()->json([
                    'message' => 'Login successful!',
                    'user' => $user,
                    'token' => $token,
                ], 200);
            } else {
                // Handle unauthorized access if user doesn't have the 'admin' role
                return response()->json(['message' => 'Unauthorized: You don\'t have the required permissions to log in'], 403);
            }


        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}

    public function logout(Request $request)
{
  $user = Auth::user();
  $user->tokens()->delete();
  return response()->json(['message' => 'Successfully logged out!']);
}
}

