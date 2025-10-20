<?php

use App\Http\Controllers\Base\Category\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for base module.
|
*/

Route::post('category', [CategoryController::class, 'createRecord']);
Route::get('category/{id}', [CategoryController::class, 'readRecord']);
Route::get('category', [CategoryController::class, 'readAllRecords']);
Route::put('category/{id}', [CategoryController::class, 'updateRecord']);
Route::delete('category/{id}', [CategoryController::class, 'deleteRecord']);
