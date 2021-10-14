<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\User\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers;

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

Route::get('test', function () {
User::where('mobile', '09196145343')->get();
    return response()->json(['hello' => 'world']);
});

Route::get('deleteUser', function (Request $request) {
    User::where('mobile', $request->mobile)->delete();
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function ($router) {
    Route::post('register', [AuthController::class, 'checkUserForRegister']); //step1 is ok
    Route::post('mobile-verification', [AuthController::class, 'sendVerificationCodeToUser']); //sms api
    Route::post('check-verification-code', [AuthController::class, 'checkVerificationCode']); // step3 is ok

    Route::post('forget-password', [ForgotPasswordController::class, 'sendResetLink']); //step1 is ok
    Route::get('reset-password', [NewPasswordController::class, 'reset']); //step1 is ok
    Route::post('login', [AuthController::class, 'signIn']);  //step1 is ok
});

Route::get('logout', function () {
    //dd(123);
    Auth::logout();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

    Route::prefix('user')->group(function ($router) {
        Route::get('/', [ProfileController::class, 'getAuthUser']);
        Route::put('update-password', [ProfileController::class, 'updatePassword']);
        Route::post('document', [DocumentController::class, 'storeForUser']);

    });

    Route::get('document/{id}', [DocumentController::class, 'show'])->name('document.show');

    Route::prefix('organization')->group(function () {
        Route::post('/', [OrganizationController::class, 'store']);

        Route::middleware('has.organization')->group(function () {
            Route::resource(
                'organization',
                OrganizationController::class
            )->only([
                'edit',
                'update',
            ]);

            Route::post('document', [DocumentController::class, 'storeForOrganization']);
        });
    });
});
