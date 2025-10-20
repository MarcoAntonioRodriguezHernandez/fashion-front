<?php

use App\Http\Controllers\Base\Store\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for stores module.
|
*/

Route::post('store', [StoreController::class, 'createRecord']);
Route::get('store/{id}', [StoreController::class, 'readRecord']);
Route::get('store', [StoreController::class, 'readAllRecords']);
Route::put('store/{id}', [StoreController::class, 'updateRecord']);
Route::delete('store/{id}', [StoreController::class, 'deleteRecord']);
