<?php

use App\Http\Controllers\Marketplace\RentDetailMarketplace\RentDetailMarketplaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for rent detail marketplace  module.
|
*/

Route::post('rent/detail/marketplace', [RentDetailMarketplaceController::class, 'createRecord']);
Route::get('rent/detail/marketplace/{id}', [RentDetailMarketplaceController::class, 'readRecord']);
Route::get('rent/detail/marketplaces', [RentDetailMarketplaceController::class, 'readAllRecords']);
Route::put('rent/detail/marketplace/{id}', [RentDetailMarketplaceController::class, 'updateRecord']);
Route::delete('rent/detail/marketplace/{id}', [RentDetailMarketplaceController::class, 'deleteRecord']);