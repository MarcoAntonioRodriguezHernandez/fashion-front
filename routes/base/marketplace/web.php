<?php

use App\Http\Controllers\Base\Marketplace\MarketplaceController;
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

Route::get('marketplaces', [MarketplaceController::class, 'readAllRecords'])->name('base.marketplace.index');
Route::get('marketplace/{id}', [MarketplaceController::class, 'readRecord'])->name('base.marketplace.show');
Route::get('marketplace/{field}/edit', [MarketplaceController::class, 'editView'])->name('base.marketplace.edit.view');
Route::put('marketplace', [MarketplaceController::class, 'updateRecord'])->name('base.marketplace.edit');
