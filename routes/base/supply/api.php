<?php

use App\Http\Controllers\Base\Supply\SupplyController;
use App\Http\Controllers\Base\Supply\SupplyCrudController;
use App\Models\Base\Supply;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('supply', [SupplyController::class, 'createRecord']);
Route::get('supply/{id}', [SupplyController::class, 'readRecord']);
Route::get('supply', [SupplyController::class, 'readAllRecords']);
Route::put('supply/{id}', [SupplyController::class, 'updateRecord']);
Route::delete('supply/{id}', [SupplyController::class, 'deleteRecord']);
