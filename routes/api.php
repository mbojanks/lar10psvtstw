<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthEmailVerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\HomeController;

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

Route::get('test', function (Request $request) {
    return response(['firstname' => 'Bojan', 'lastname' => 'Milosavljevic'], 200);
});

Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);

Route::put('lang',[HomeController::class,'setLocale']);

//email verification API routes
Route::get("email/resend", [AuthEmailVerificationController::class, 'resend'])
    ->name('verification.resend');
Route::get("email/test", [AuthEmailVerificationController::class, 'test']);
// forgotten email address?

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('logout',[AuthController::class,'logout']);

    Route::get('user',[AuthController::class,'user']);
    Route::put('users/info',[AuthController::class,'updateInfo']);
    Route::put('users/password',[AuthController::class,'updatePassword']);
    Route::put('users/{id}/role', [UserController::class,'updateRole']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('roles',RoleController::class);
    Route::apiResource('permissions',PermissionController::class)->only('index');
});
