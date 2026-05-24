<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UploadController;
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

Route::get('upload/view/{uuid}', [UploadController::class, 'view']);
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

    Route::get('identitas', [UserController::class, 'dataIdentitas']);
    Route::put('identitas', [UserController::class, 'ubahDataIdenitas']);

    Route::apiResource('upload', UploadController::class)->except(['update']);
    /*
    |--------------------------------------------------------------------------
    | profil
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Pengelola,Dosen,Admin')->group(function () {
        Route::get('pegawai/profil', [PegawaiController::class, 'showProfil']);
        Route::put('pegawai/profil', [PegawaiController::class, 'simpanProfil']);
    });

    Route::middleware('role:Mahasiswa')->group(function () {
        Route::get('mahasiswa/profil', [MahasiswaController::class, 'showProfil']);
        Route::put('mahasiswa/profil', [MahasiswaController::class, 'simpanProfil']);
    });

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
