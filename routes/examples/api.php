<?php

use App\Http\Controllers\Examples\SampleCrudController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('example', [SampleCrudController::class, 'createRecord']);
Route::get('example/{id}', [SampleCrudController::class, 'readRecord']);
Route::get('example', [SampleCrudController::class, 'readAllRecords']);
Route::put('example/{id}', [SampleCrudController::class, 'updateRecord']);
Route::delete('example/{id}', [SampleCrudController::class, 'deleteRecord']);
