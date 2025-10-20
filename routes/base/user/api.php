<?php

use App\Http\Controllers\Base\Users\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for users module.
|
*/

Route::post('user', [UserController::class, 'createRecord']);
Route::get('user/{id}', [UserController::class, 'readRecord']);
Route::get('user', [UserController::class, 'readAllRecords']);
Route::put('user/{id}', [UserController::class, 'updateRecord']);
Route::delete('user/{id}', [UserController::class, 'deleteRecord']);
