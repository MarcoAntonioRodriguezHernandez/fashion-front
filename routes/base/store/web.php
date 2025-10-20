<?php

use App\Http\Controllers\Base\Store\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for stores module.
|
*/

Route::get('store', [StoreController::class, 'readAllRecords'])->name('base.store.index');
Route::get('store/create', [StoreController::class, 'createView'])->name('base.store.create.view');
Route::get('store/{id}', [StoreController::class, 'readRecord'])->name('base.store.show');
Route::get('store/{field}/edit', [StoreController::class, 'editView'])->name('base.store.edit.view');
Route::post('store', [StoreController::class, 'createRecord'])->name('base.store.create');
Route::put('store', [StoreController::class, 'updateRecord'])->name('base.store.edit');
Route::delete('store/{field}', [StoreController::class, 'deleteRecord'])->name('base.store.delete');
