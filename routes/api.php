<?php

use App\Enumerations\DocumentTypeEnums;
use App\Http\Controllers\Announcement\AnnouncementController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\News\NewsController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\Thread\ThreadController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\User\ProfileController;
use App\Models\Enumeration;
use App\Models\User;
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

Route::post('test', function (Request $request) {

    $fileUploaded = uploadFile($request->file, DocumentTypeEnums::THREAD, 33);

    dd($fileUploaded);

    User::where('mobile', '09196145343')->get();
    return response()->json(['hello' => 'world']);
});

Route::get('deleteUser', function (Request $request) {
    User::where('mobile', $request->mobile)->delete();
});

Route::get('organization/category', function (Request $request) {
    return Enumeration::where('parent_id', 4)->get();
});

Route::get('organization/type', function (Request $request) {
    return Enumeration::where('parent_id', 1)->get();
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
    Auth::logout();
    return ["message" => "عملیات خروج با موفقیت انجام شد", 'logout' => true, 'result' => true];
});


Route::middleware('auth:sanctum')->group(function ($router) {
    Route::post('login', ['as' => 'login', 'uses' => 'LoginController@do']);

    Route::prefix('user')->group(function ($router) {
        Route::get('/', [ProfileController::class, 'getAuthUser']);
        Route::put('update-password', [ProfileController::class, 'updatePassword']);
        Route::post('document', [DocumentController::class, 'storeForUser']);

    });

    Route::prefix('announcement')->group(function ($router) {
        Route::get('/', [AnnouncementController::class, 'index']);
        Route::post('/', [AnnouncementController::class, 'store']);
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy']);
    });

    Route::get('document/{id}', [DocumentController::class, 'show'])->name('document.show');
    Route::get('file/{id}', [FileController::class, 'show']);

    Route::prefix('organization')->group(function () {
        Route::post('/', [OrganizationController::class, 'store']);

        Route::middleware('has.organization')->group(function () {

            Route::get('/', [OrganizationController::class, 'show']);
            Route::put('/', [OrganizationController::class, 'update']);

            Route::post('document', [DocumentController::class, 'storeForOrganization']);
            Route::get('document', [DocumentController::class, 'index']);

            Route::post('documents-completed', [OrganizationController::class, 'documentsCompleted']);
        });
    });

    //route tickets
    $router->prefix('tickets')->group(function ($router) {
        $router->get('/', [TicketController::class, 'index']);
        $router->middleware('has.organization')->group(function ($router) {
            $router->get('/get-organization-tickets', [TicketController::class, 'getOrganizationTickets']);
            $router->get('/get-organization-ticket/{ticketId}', [TicketController::class, 'getOrganizationTicket']);
            $router->post('/send-ticket-to-management', [TicketController::class, 'sendTicketToManagement']);
        });
    });
    //route threads
    $router->middleware('has.organization')->prefix('threads')->group(function ($router) {
        $router->post('/create-thread-to-management', [ThreadController::class, 'createThreadToManagement']);
    });

        $router->middleware('has.organization')->group(function ($router) {
            $router->resource(
                'news',
                NewsController::class
            );
            $router->post('news/upload-file', [NewsController::class, 'uploadFileNews']);
        });
});

Route::get('organizations', [OrganizationController::class, 'index']);
Route::post('/create-people-ticket', [TicketController::class, 'createPeopleTicket']);


