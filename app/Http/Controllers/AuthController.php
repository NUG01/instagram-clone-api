<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function login(LoginRequest $request)
	{

		$username=$request->username;
		$password=$request->password;
		$usernameType = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$token = auth()->attempt([$usernameType=>$username, 'password'=>$password]);
		if(!$token){
			return response()->json('User does not exist!', 403);
		} 
		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => User::where($usernameType, $username)->first()->id,
		];

		$jwt= JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		$cookie = cookie('access_token', $jwt, 30, '/', env('FRONTEND_URL'), true, true, false, 'Strict');
		return response()->json('Logged in successfully!')->withCookie($cookie);
	}

	public function logout(){
		$cookie = cookie('access_token', '', 0, '/', env('FRONTEND_URL'), true, true, false, 'Strict');

		return response()->json('Successfully logged out')->withCookie($cookie);
	}

	public function register(RegisterRequest $request)
	{
		$code = DB::table('codes')->where('user_email', $request->email)->where('code', $request->code)->latest()->first();
		if ($code != null)
		{
			$user = User::create([
				'email'              => Str::lower($request->email),
				'username'           => $request->username,
				'fullname'           => $request->fullname,
				'birth_date'         => $request->birth_date,
				'password'           => Hash::make($request->password),
				'verification_code'  => sha1(time()),
				'is_verified'        => 1,
			]);

			$this->insertCode($user);
			return response()->json('Successfully registered!');
		}
		else
		{
			return response()->json('Provided code is incorrect!');
		}
	}

	public function checkCredentials(Request $request)
	{
		$email = Str::lower($request->email);
		$username = $request->username;

		$emailExists = User::where('email', $email)->first();
		$usernameExists = User::where('username', $username)->first();

		if (!$emailExists && !$usernameExists)
		{
			return response()->json('Credentials successfully registered!');
		}
		if ($emailExists && $usernameExists)
		{
			return response()->json('Given email and username already exist!', 403);
		}
		if ($emailExists)
		{
			return response()->json('Email already exists!', 403);
		}
		if ($usernameExists)
		{
			return response()->json('Username already exists!', 403);
		}
	}

	public function resendCode(Request $request)
	{
		$user = User::where('email', $request->email)->first();

		$this->insertCode($user);
	}

	public function addVerificationCode(Request $request)
	{
		$randomNumber = mt_rand(100000, 999999);

		DB::table('codes')->insert([
			'user_email'=> Str::lower($request->email),
			'code'      => $randomNumber,
			'created_at'=> Carbon::now(),
			'updated_at'=> Carbon::now(),
		]);

		MailController::sendEmail($request->username, Str::lower($request->email), $randomNumber, $randomNumber . ' is your Instagram code', 'emails.register');
	}

	private function insertCode($user)
	{
		$randomNumber = mt_rand(100000, 999999);

		DB::table('codes')->insert([
			'user_email'=> $user->email,
			'code'      => $randomNumber,
			'created_at'=> Carbon::now(),
			'updated_at'=> Carbon::now(),
		]);

		if ($user != null)
		{
			MailController::sendEmail($user->username, $user->email, $randomNumber, $randomNumber . ' is your Instagram code', 'emails.register');
		}
		else
		{
			$user->delete();
			return response()->json('Registration failed!', 403);
		}
	}
}
