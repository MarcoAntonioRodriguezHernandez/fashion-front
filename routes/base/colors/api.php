<?php

use App\Http\Controllers\Base\Colors\ColorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for tags module.
|
*/

Route::post('colors', [ColorController::class, 'createRecord']);
Route::get('colors/{id}', [ColorController::class, 'readRecord']);
Route::get('colors', [ColorController::class, 'readAllRecords']);
Route::put('colors/{id}', [ColorController::class, 'updateRecord']);
Route::delete('colors/{id}', [ColorController::class, 'deleteRecord']);
