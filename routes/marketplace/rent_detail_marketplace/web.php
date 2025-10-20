<?php

use App\Http\Controllers\Marketplace\RentDetailMarketplace\RentDetailMarketplaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('rent/detail/marketplace', [RentDetailMarketplaceController::class, 'readAllRecords'])->name('marketplace.rent_detail_marketplace.index');
Route::get('rent/detail/marketplace/create', [RentDetailMarketplaceController::class, 'createView'])->name('marketplace.rent_detail_marketplace.create.view');
Route::get('rent/detail/marketplace/{id}', [RentDetailMarketplaceController::class, 'readRecord'])->name('marketplace.rent_detail_marketplace.show');
Route::get('rent/detail/marketplace/{field}/edit', [RentDetailMarketplaceController::class, 'editView'])->name('marketplace.rent_detail_marketplace.edit.view');
Route::post('rent/detail/marketplace', [RentDetailMarketplaceController::class, 'createRecord'])->name('marketplace.rent_detail_marketplace.create');
Route::put('rent/detail/marketplace', [RentDetailMarketplaceController::class, 'updateRecord'])->name('marketplace.rent_detail_marketplace.edit');
Route::delete('rent/detail/marketplace/{field}', [RentDetailMarketplaceController::class, 'deleteRecord'])->name('marketplace.rent_detail_marketplace.delete');