<?php

use App\Http\Controllers\Marketplace\PaymentOrderMarketplace\PaymentOrderMarketplaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for payment order marketplace  module.
|
*/

Route::post('payment/order/marketplace', [PaymentOrderMarketplaceController::class, 'createRecord']);
Route::get('payment/order/marketplace/{id}', [PaymentOrderMarketplaceController::class, 'readRecord']);
Route::get('payment/order/marketplaces', [PaymentOrderMarketplaceController::class, 'readAllRecords']);
Route::put('payment/order/marketplace/{id}', [PaymentOrderMarketplaceController::class, 'updateRecord']);
Route::delete('payment/order/marketplace/{id}', [PaymentOrderMarketplaceController::class, 'deleteRecord']);