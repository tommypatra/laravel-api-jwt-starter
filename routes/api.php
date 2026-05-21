<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FakultasController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::post('auth-check', [AuthController::class, 'authCheck']);

Route::post(
    'auth-web',
    [AuthController::class, 'authWeb']
)->middleware('throttle:auth-web');

Route::get('auth/google', [AuthController::class, 'redirectGoogle']);
Route::get('auth/callback', [AuthController::class, 'callbackGoogle']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth.token-jwt',
    'throttle:jwt-api',
])->group(function () {

    Route::get('check-token', [AuthController::class, 'checkToken']);
    Route::post('logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Sync Fakultas
    |--------------------------------------------------------------------------
    */

    Route::middleware([
        'role:1,3',
        'throttle:sync-api',
    ])->prefix('fakultas')->group(function () {
        Route::get('preview-sync', [FakultasController::class, 'previewSync']);
        Route::post('sync', [FakultasController::class, 'sync']);
    });

    /*
    |--------------------------------------------------------------------------
    | CRUD Fakultas
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:1')->group(function () {
        Route::apiResource('fakultas', FakultasController::class);
    });

});
