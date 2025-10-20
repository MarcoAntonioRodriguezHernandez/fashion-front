<?php

use App\Http\Controllers\Base\SuppliedItem\SuppliedItemController;
use App\Http\Controllers\Base\SupplyTransfer\SupplyTransferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('supply-transfer', [SupplyTransferController::class, 'createRecord']);
Route::get('supply-transfer/{id}', [SuppliedItemController::class, 'readRecord']);
Route::get('supply-transfer', [SuppliedItemController::class, 'readAllRecords']);
Route::put('supply-transfer/{id}', [SuppliedItemController::class, 'updateRecord']);
Route::delete('supply-transfer/{id}', [SuppliedItemController::class, 'deleteRecord']);
