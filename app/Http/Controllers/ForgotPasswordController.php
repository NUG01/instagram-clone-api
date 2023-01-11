<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\RecoverPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
	public function sendEmail(ForgotPasswordRequest $request)
	{
		$user = null;
		$emailValue = User::where('email', $request->emailOrUsername)->first();
		$usernameValue = User::where('username', $request->emailOrUsername)->first();
		if ($emailValue)
		{
			$user = $emailValue;
		}
		if ($usernameValue)
		{
			$user = $usernameValue;
		}
		$code = ([
			'reset'=> env('FRONTEND_URL_FOR_CONFIRM') . '/recover-password/' . $user->verification_code . '?email=' . $user->email,
			'login'=> env('FRONTEND_URL_FOR_CONFIRM') . '/easy-login/' . $user->verification_code . '?email=' . $user->email,
		]);
		if ($user != null)
		{
			MailController::sendEmail($user->username, Str::lower($user->email), $code, $user->username . ", we've made it easy to get back on Instagram", 'emails.forgotPassword');
		}
		else
		{
			return response()->json('User with this email or username does not exist!', 403);
		}
	}

	public function recoverPassword(RecoverPasswordRequest $request)
	{
		$user = User::where('email', $request->email)->where('verification_code', $request->token)->first();
		$user->password = Hash::make($request->password);
		$user->save();
		return response()->json('Password updated successfully!');
	}

	public function loginFromEmail(RecoverPasswordRequest $request)
	{
		$user = User::where('email', $request->email)->where('verification_code', $request->token)->first();
		if (!$user)
		{
			return response()->json('User does not exist!', 403);
		}
		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => User::where('email', $user->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		$cookie = cookie('access_token', $jwt, 30, '/', env('FRONTEND_URL'), true, true, false, 'Strict');
		return response()->json('Logged in successfully!')->withCookie($cookie);
	}
}
