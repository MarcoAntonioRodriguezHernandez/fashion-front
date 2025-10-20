<?php

use App\Http\Controllers\Base\SuppliedItem\SuppliedItemController;
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

Route::get('supply-item', [SuppliedItemController::class, 'readAllRecords'])->name('base.supply_item.index');
Route::get('supply-item/create', [SuppliedItemController::class, 'createView'])->name('base.supply_item.create.view');
Route::get('supply-item/{id}', [SuppliedItemController::class, 'readRecord'])->name('base.supply_item.show');
Route::get('supply-item/{field}/edit', [SuppliedItemController::class, 'editView'])->name('base.supply_item.edit.view');
Route::post('supply-item', [SuppliedItemController::class, 'createRecord'])->name('base.supply_item.create');
Route::put('supply-item', [SuppliedItemController::class, 'updateRecord'])->name('base.supply_item.edit');
Route::delete('supply-item/{field}', [SuppliedItemController::class, 'deleteRecord'])->name('base.supply_item.delete');