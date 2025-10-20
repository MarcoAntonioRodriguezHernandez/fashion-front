<?php

use App\Http\Controllers\Base\Marketplace\MarketplaceController;
use App\Http\Controllers\Base\Marketplace\OrderMarketplace\OrderMarketplaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for tags module.
|
*/

Route::post('marketplace', [MarketplaceController::class, 'createRecord']);
Route::get('marketplace/{id}', [MarketplaceController::class, 'readRecord']);
Route::get('marketplaces', [MarketplaceController::class, 'readAllRecords']);
Route::put('marketplace/{id}', [MarketplaceController::class, 'updateRecord']);
Route::delete('marketplace/{id}', [MarketplaceController::class, 'deleteRecord']);
