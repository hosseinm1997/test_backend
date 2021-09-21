<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function ($router) {
    Route::get('register', [AuthController::class, 'checkUserForRegister']); //step1 is ok
    Route::get('mobile-verification', [AuthController::class, 'sendVerificationCodeToUser']); //sms api
    Route::get('check-verification-code', [AuthController::class, 'checkVerificationCode']); // step3 is ok

    Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLink']); //step1 is ok
    Route::post('reset-password', [NewPasswordController::class, 'reset']); //step1 is ok
    Route::post('login', [NewPasswordController::class, 'signIn']); //step1 is ok
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('logout', function () {
        Auth::logout();
    });

    Route::prefix('user')->group(function ($router) {
        Route::put('update-password', [ProfileController::class, 'updatePassword']);
    });

    Route::resource(
        'organization',
        \App\Http\Controllers\OrganizationController::class
    )->only([
        'store',
        'edit',
        'update',
    ]);
});
