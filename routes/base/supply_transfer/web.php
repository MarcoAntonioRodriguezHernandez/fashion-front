<?php

use App\Http\Controllers\Base\SupplyTransfer\SupplyTransferController;
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

Route::get('supply-transfer', [SupplyTransferController::class, 'readAllRecords'])->name('base.supply_transfer.index');
Route::get('supply-transfer/create', [SupplyTransferController::class, 'createView'])->name('base.supply_transfer.create.view');
Route::get('supply-transfer/{id}', [SupplyTransferController::class, 'readRecord'])->name('base.supply_transfer.show');
Route::get('supply-transfer/{field}/edit', [SupplyTransferController::class, 'editView'])->name('base.supply_transfer.edit.view');
Route::post('supply-transfer', [SupplyTransferController::class, 'createRecord'])->name('base.supply_transfer.create');
Route::put('supply-transfer', [SupplyTransferController::class, 'updateRecord'])->name('base.supply_transfer.edit');
Route::delete('supply-transfer/{field}', [SupplyTransferController::class, 'deleteRecord'])->name('base.supply_transfer.delete');