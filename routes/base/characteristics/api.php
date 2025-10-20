<?php

use App\Http\Controllers\Base\Characteristics\CharacteristicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for base module.
|
*/

Route::post('characteristics', [CharacteristicController::class, 'createRecord']);
Route::get('characteristics/{id}', [CharacteristicController::class, 'readRecord']);
Route::get('characteristics', [CharacteristicController::class, 'readAllRecords']);
Route::put('characteristics/{id}', [CharacteristicController::class, 'updateRecord']);
Route::delete('characteristics/{id}', [CharacteristicController::class, 'deleteRecord']);
