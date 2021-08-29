<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {

    dd($request);
//    dd(2);
  //  return $request->user();
});
Route::get('login', [ 'as' => 'login', 'uses' => 'LoginController@do']);

Route::get('register',[UserController::class, 'checkDuplicateUser']);
