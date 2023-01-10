<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $user=null;
        if(jwtUser()) $user=jwtUser();
        return response()->json(['user' => $user]);
        }
}
