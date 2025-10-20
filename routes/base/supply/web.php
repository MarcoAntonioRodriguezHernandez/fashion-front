<?php

use App\Http\Controllers\Base\Supply\SupplyController;
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

Route::get('supply', [SupplyController::class, 'readAllRecords'])->name('base.supply.index');
Route::get('supply/{field}/report', [SupplyController::class, 'printReport'])->name('base.supply.report.print');
Route::get('supply/create/{productId?}', [SupplyController::class, 'fullCreateView'])->name('base.supply.create.view');
Route::post('supply/search', [SupplyController::class, 'searchProductAction'])->name('base.supply.create.search');
Route::get('supply/store/{storeId}', [SupplyController::class, 'showByStore'])->name('base.supply.show_store');
Route::get('supply/{id}', [SupplyController::class, 'readRecord'])->name('base.supply.show');
Route::get('supply/{field}/edit', [SupplyController::class, 'editView'])->name('base.supply.edit.view');
Route::post('supply', [SupplyController::class, 'createRecord'])->name('base.supply.create');
Route::put('supply', [SupplyController::class, 'updateRecord'])->name('base.supply.edit');
Route::delete('supply/{field}', [SupplyController::class, 'deleteRecord'])->name('base.supply.delete');
