<?php

use App\Http\Controllers\Auth\{
    ApiAuthController,
    SanctumController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
*/

Route::controller(SanctumController::class)->group(function () {
    Route::post('/sanctum/register', 'register');

    Route::post('/sanctum/login', 'login');

    Route::post('/sanctum/logout', 'logout')->middleware('auth:sanctum');
});

Route::controller(ApiAuthController::class)->group(function () {
    Route::post('/login', 'login');

    Route::get('/get-account', 'getAccount');

    Route::post('/logout', 'logout');

    Route::post('/refresh', 'refresh');
});
