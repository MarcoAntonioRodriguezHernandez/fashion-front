<?php

use App\Http\Controllers\Marketplace\ItemOrderMarketplace\ItemOrderMarketplaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for product order marketplace module.
|
*/

Route::post('item/order/marketplace', [ItemOrderMarketplaceController::class, 'createRecord']);
Route::get('item/order/marketplace/{id}', [ItemOrderMarketplaceController::class, 'readRecord']);
Route::get('item/order/marketplaces', [ItemOrderMarketplaceController::class, 'readAllRecords']);
Route::put('item/order/marketplace/{id}', [ItemOrderMarketplaceController::class, 'updateRecord']);
Route::delete('item/order/marketplace/{id}', [ItemOrderMarketplaceController::class, 'deleteRecord']);