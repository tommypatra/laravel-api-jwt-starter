<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebController::class, 'login']);
Route::get('/login', [WebController::class, 'login']);
Route::get('/dashboard', [WebController::class, 'dashboard']);

Route::get('/api-sevima/login', [WebController::class, 'loginSevima']);
Route::get('/api-sevima/{api_keyword}/{id?}/{id2?}', [WebController::class, 'index']);
