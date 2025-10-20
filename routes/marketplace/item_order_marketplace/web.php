<?php

use App\Http\Controllers\Marketplace\ItemOrderMarketplace\ItemOrderMarketplaceController;
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

Route::get('item/order/marketplace', [ItemOrderMarketplaceController::class, 'readAllRecords'])->name('marketplace.item_order_marketplace.index');
Route::get('item/order/marketplace/create', [ItemOrderMarketplaceController::class, 'createView'])->name('marketplace.item_order_marketplace.create.view');
Route::get('item/order/marketplace/{id}', [ItemOrderMarketplaceController::class, 'readRecord'])->name('marketplace.item_order_marketplace.show');
Route::match(['get', 'post'], 'item/order/marketplace/{field}/edit', [ItemOrderMarketplaceController::class, 'editView'])->name('marketplace.item_order_marketplace.edit.view')->middleware('employee');
Route::post('item/order/marketplace', [ItemOrderMarketplaceController::class, 'createRecord'])->name('marketplace.item_order_marketplace.create');
Route::put('item/order/marketplace', [ItemOrderMarketplaceController::class, 'updateRecord'])->name('marketplace.item_order_marketplace.edit');
Route::delete('item/order/marketplace/{field}', [ItemOrderMarketplaceController::class, 'deleteRecord'])->name('marketplace.item_order_marketplace.delete');
