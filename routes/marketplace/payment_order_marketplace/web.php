<?php

use App\Http\Controllers\Marketplace\PaymentOrderMarketplace\PaymentOrderMarketplaceController;
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

Route::get('payment/order/marketplace', [PaymentOrderMarketplaceController::class, 'readAllRecords'])->name('marketplace.payment_order_marketplace.index');
Route::get('payment/order/marketplace/create', [PaymentOrderMarketplaceController::class, 'createView'])->name('marketplace.payment_order_marketplace.view');
Route::get('payment/order/marketplace/{id}', [PaymentOrderMarketplaceController::class, 'readRecord'])->name('marketplace.payment_order_marketplace.show');
Route::get('payment/order/marketplace/{field}/edit', [PaymentOrderMarketplaceController::class, 'editView'])->name('payment_order_marketplace.edit.view');
Route::post('payment/order/marketplace', [PaymentOrderMarketplaceController::class, 'createRecord'])->name('payment_order_marketplace.create');
Route::put('payment/order/marketplace', [PaymentOrderMarketplaceController::class, 'updateRecord'])->name('payment_order_marketplace.edit');
Route::delete('payment/order/marketplace/{field}', [PaymentOrderMarketplaceController::class, 'deleteRecord'])->name('payment_order_marketplace.delete');
