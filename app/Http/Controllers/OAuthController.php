<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback()
	{
		$googleUser = Socialite::driver('google')->stateless()->user();

		if (User::where('email', $googleUser->email)->where('google_id', null)->first())
		{
			return redirect(env('FRONTEND_URL_FOR_CONFIRM') . '/');
		}
		$user = User::updateOrCreate(
			[
				'google_id' => $googleUser->id,
			],
			[
				'username'             => Str::lower(strtok($googleUser->email, '@')),
				'fullname'             => $googleUser->name,
				'email'                => $googleUser->email,
				'google_token'         => $googleUser->token,
				'verification_code'    => sha1(time()),
				'password'             => bcrypt($googleUser->id),
				'is_verified'          => 1,
				'google_refresh_token' => $googleUser->refreshToken,
			]
		);
		if(!$user->theme){
			$theme=new Theme();
			$theme->user_id=$user->id;
			$theme->color='light';
			$theme->save();
		}

		$token = auth()->attempt(['email' => $googleUser->email, 'password'=>$googleUser->id]);
		if (!$token)
		{
			return response()->json(['error' => 'User Does not exist!'], 401);
		}

		$payload = [
			'exp' => Carbon::now()->addMinutes(30)->timestamp,
			'uid' => User::where('email', $googleUser->email)->first()->id,
		];

		$jwt = JWT::encode($payload, config('auth.jwt_secret'), 'HS256');
		$cookie = cookie('access_token', $jwt, 30, '/', env('FRONTEND_URL'), true, true, false, 'Strict');
		return redirect(env('FRONTEND_URL_FOR_CONFIRM') . '/oauth')->withCookie($cookie);
	}
}
