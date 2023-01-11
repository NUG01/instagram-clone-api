<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
	public function index()
	{
		$user = null;
		if (jwtUser())
		{
			$user = jwtUser();
		}
		return response()->json(['user' => $user]);
	}
}
