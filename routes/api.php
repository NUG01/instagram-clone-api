<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ReportController;
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
	Route::post('/logout', 'logout')->name('user.logout');
	Route::post('/check-credentials', 'checkCredentials')->name('user.check');
	Route::post('/insert-code', 'addVerificationCode')->name('user.verify');
	Route::post('/resend-code', 'resendCode')->name('user.code');
});

Route::controller(OAuthController::class)->prefix('auth/google')->group(function () {
	Route::get('/redirect', 'redirect')->middleware('web')->name('google.redirect');
	Route::get('/callback', 'callback')->name('google.callback');
});

Route::controller(UserController::class)->prefix('user')->group(function () {
	Route::get('/', 'index')->name('user.index');
});
Route::controller(ReportController::class)->group(function () {
	Route::post('/report-problem', 'create')->name('report.create');
});

Route::controller(ForgotPasswordController::class)->group(function () {
	Route::post('/forgot-password', 'sendEmail')->name('password.forgot');
	Route::post('/recover-password', 'recoverPassword')->name('password.recover');
	Route::post('/easy-login', 'loginFromEmail')->name('password.login');
});
