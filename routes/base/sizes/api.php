<?php

use App\Http\Controllers\Base\Sizes\SizesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('sizes', [SizesController::class, 'createRecord']);
Route::get('sizes/{id}', [SizesController::class, 'readRecord']);
Route::get('sizes', [SizesController::class, 'readAllRecords']);
Route::put('sizes/{id}', [SizesController::class, 'updateRecord']);
Route::delete('sizes/{id}', [SizesController::class, 'deleteRecord']);
