<?php

use App\Http\Controllers\Base\ShippingPrice\ShippingPriceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for Shipping Prices module.
|
*/

Route::get('shipping-prices', [ShippingPriceController::class, 'readAllRecords'])->name('base.shipping_prices.index');
Route::get('shipping-prices/create', [ShippingPriceController::class, 'createView'])->name('base.shipping_prices.create.view');
Route::get('shipping-prices/{id}', [ShippingPriceController::class, 'readRecord'])->name('base.shipping_prices.show');
Route::get('shipping-prices/{field}/edit', [ShippingPriceController::class, 'editView'])->name('base.shipping_prices.edit.view');
Route::post('shipping-prices', [ShippingPriceController::class, 'createRecord'])->name('base.shipping_prices.create');
Route::put('shipping-prices', [ShippingPriceController::class, 'updateRecord'])->name('base.shipping_prices.edit');
Route::delete('shipping-prices/{field}', [ShippingPriceController::class, 'deleteRecord'])->name('base.shipping_prices.delete');
