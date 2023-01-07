<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
	Route::post('/register', 'register')->name('user.register');
	Route::post('/login', 'login')->name('user.login');
	Route::post('/check-credentials', 'checkCredentials')->name('user.check');
	Route::post('/resend-code', 'resendCode')->name('user.code');
	Route::post('/insert-code', 'addVerificationCode')->name('user.verify');
	// Route::post('/logout', 'logout')->name('user.logout');
});
