<?php

use App\Http\Controllers\Base\Designers\DesignersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Designers module.
|
*/

Route::post('designers', [DesignersController::class, 'createRecord']);
Route::get('designers/{id}', [DesignersController::class, 'readRecord']);
Route::get('designers', [DesignersController::class, 'readAllRecords']);
Route::put('designers/{id}', [DesignersController::class, 'updateRecord']);
Route::delete('designers/{id}', [DesignersController::class, 'deleteRecord']);
