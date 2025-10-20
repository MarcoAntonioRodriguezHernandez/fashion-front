<?php

use App\Http\Controllers\Base\ShippingPrice\ShippingPriceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for tags module.
|
*/

Route::post('shipping-prices', [ShippingPriceController::class, 'createRecord']);
Route::get('shipping-prices/{id}', [ShippingPriceController::class, 'readRecord']);
Route::get('shipping-prices', [ShippingPriceController::class, 'readAllRecords']);
Route::put('shipping-prices/{id}', [ShippingPriceController::class, 'updateRecord']);
Route::delete('shipping-prices/{id}', [ShippingPriceController::class, 'deleteRecord']);
