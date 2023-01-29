<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function changeThemeColor(Request $request){
      $theme=Theme::where('user_id', jwtUser()->id)->latest()->first();
      $theme->delete();
      $theme->insert(['color'=>$request->themeColor, 'user_id'=> jwtUser()->id]);
      return response()->json($theme['color']);
    }
}
