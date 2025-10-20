<?php

use App\Http\Controllers\Base\SuppliedItem\SuppliedItemController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('supply-item', [SuppliedItemController::class, 'createRecord']);
Route::get('supply-item/{id}', [SuppliedItemController::class, 'readRecord']);
Route::get('supply-item', [SuppliedItemController::class, 'readAllRecords']);
Route::put('supply-item/{id}', [SuppliedItemController::class, 'updateRecord']);
Route::delete('supply-item/{id}', [SuppliedItemController::class, 'deleteRecord']);
