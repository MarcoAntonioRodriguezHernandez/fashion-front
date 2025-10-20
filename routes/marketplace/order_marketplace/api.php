<?php

use App\Http\Controllers\Marketplace\OrderMarketplace\{
    FullOrderMarketplaceController,
    OrderMarketplaceController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for order marketplace  module.
|
*/

Route::post('order/marketplace', [OrderMarketplaceController::class, 'createRecord']);

Route::post('order/marketplace/full', [FullOrderMarketplaceController::class, 'createRecord']);

Route::get('order/marketplace/{id}', [OrderMarketplaceController::class, 'readRecord']);
Route::get('order/marketplace', [OrderMarketplaceController::class, 'readAllRecords']);
Route::put('order/marketplace/{id}', [OrderMarketplaceController::class, 'updateRecord']);
Route::delete('order/marketplace/{id}', [OrderMarketplaceController::class, 'deleteRecord']);
Route::put('order/marketplace/{id}/status', [OrderMarketplaceController::class, 'updateStatus']);
