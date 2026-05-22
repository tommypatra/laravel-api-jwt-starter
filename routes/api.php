<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::post('auth/login-siakad', [AuthController::class, 'authCheck']);

Route::post(
    'auth/login-web',
    [AuthController::class, 'authWeb']
)->middleware('throttle:auth-web');

Route::get('auth/login-google', [AuthController::class, 'redirectGoogle']);
Route::get('auth/login-google/callback', [AuthController::class, 'callbackGoogle']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth.token-jwt',
    'throttle:jwt-api',
])->group(function () {

    Route::get('auth/validate', [AuthController::class, 'validate']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Sync Fakultas
    |--------------------------------------------------------------------------
    */

    // Route::middleware([
    //     'role:Admin,Pengelola',
    //     'throttle:sync-api',
    // ])->prefix('fakultas')->group(function () {
    //     Route::get('preview-sync', [FakultasController::class, 'previewSync']);
    //     Route::post('sync', [FakultasController::class, 'sync']);
    // });

    /*
    |--------------------------------------------------------------------------
    | CRUD roles
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin')->group(function () {
        Route::apiResource('user', UserController::class);
        Route::put('user/{id}/roles', [UserController::class, 'updateRoles']);

        Route::apiResource('role', RoleController::class);
    });

});
