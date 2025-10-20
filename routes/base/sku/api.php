<?php

use App\Http\Controllers\Base\Sku\SkuController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('sku', [SkuController::class, 'createRecord']);
Route::get('sku/{id}', [SkuController::class, 'readRecord']);
Route::get('sku', [SkuController::class, 'readAllRecords']);
Route::put('sku/{id}', [SkuController::class, 'updateRecord']);
Route::delete('sku/{id}', [SkuController::class, 'deleteRecord']);
