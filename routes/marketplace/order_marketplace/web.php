<?php

use App\Http\Controllers\Marketplace\OrderMarketplace\{
    FullOrderMarketplaceController,
    OrderMarketplaceController,
};
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

Route::get('order/marketplace', [OrderMarketplaceController::class, 'readAllRecords'])->name('marketplace.order_marketplace.index');

Route::match(['get', 'post'], 'order/marketplace/full-create', [FullOrderMarketplaceController::class, 'fullCreateView'])->name('marketplace.order_marketplace.full_create.view')->middleware('employee');
Route::post('order/marketplace/full', [FullOrderMarketplaceController::class, 'createRecord'])->name('marketplace.order_marketplace.full_create')->middleware('employee');

Route::get('order/marketplace/create', [OrderMarketplaceController::class, 'createView'])->name('marketplace.order_marketplace.create.view');
Route::get('order/marketplace/{id}', [OrderMarketplaceController::class, 'readRecord'])->name('marketplace.order_marketplace.show');
Route::get('order/marketplace/{field}/edit', [OrderMarketplaceController::class, 'editView'])->name('marketplace.order_marketplace.edit.view');
Route::post('order/marketplace', [OrderMarketplaceController::class, 'createRecord'])->name('marketplace.order_marketplace.create');
Route::put('order/marketplace', [OrderMarketplaceController::class, 'updateRecord'])->name('marketplace.order_marketplace.edit');
Route::delete('order/marketplace/{field}', [OrderMarketplaceController::class, 'deleteRecord'])->name('marketplace.order_marketplace.delete');
Route::put('order/marketplace/{id}/status', [OrderMarketplaceController::class, 'updateStatus'])->name('marketplace.order_marketplace.update_status');
Route::get('order/marketplace/{id}/docs/{type}', [OrderMarketplaceController::class, 'showDocumentation'])->name('marketplace.order_marketplace.show_docs');
Route::get('order/marketplace/{id}/docsale/{type}', [OrderMarketplaceController::class, 'showDocumentationSale'])->name('marketplace.order_marketplace.show_docs_sale');
Route::get('order/marketplace/{id}/docsaleRent/{type}', [OrderMarketplaceController::class, 'showDocumentationSaleRent'])->name('marketplace.order_marketplace.show_docs_sale_rent');
