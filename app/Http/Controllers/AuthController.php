<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return $request;
    }
    public function register(RegisterRequest $request)
    {

        $user = User::create([
         'email'  => $request->email,
         'username'  => $request->username,
         'fullname'  => $request->fullname,
         'birth_date'  => $request->birth_date,
         'password'  => Hash::make($request->password),
         'verification_code'  => sha1(time()),
        ]);

        if($user != null){
		MailController::sendEmail($user->username, $user->email, $user->verification_code, 'Account Confirmation', 'emails.register');
        }else{
            return response()->json('Registration failed!', 403);
        }
        return response()->json('Email sent successfully!');
    }
}
