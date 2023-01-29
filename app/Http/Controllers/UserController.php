<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
	public function index()
	{
		$user = null;
		$theme = null;
		if (jwtUser())
		{
			$user = jwtUser();
			$theme=$user->theme['color'];
		}
		return response()->json(['user' => $user, 'theme'=>$theme]);
	}
}
