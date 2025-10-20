<?php

use App\Http\Controllers\Base\Sku\SkuController;
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

Route::get('sku', [SkuController::class, 'readAllRecords'])->name('base.sku.index');
Route::get('sku/create', [SkuController::class, 'createView'])->name('base.sku.create.view');
Route::get('sku/{id}', [SkuController::class, 'readRecord'])->name('base.sku.show');
Route::get('sku/{field}/edit', [SkuController::class, 'editView'])->name('base.sku.edit.view');
Route::post('sku', [SkuController::class, 'createRecord'])->name('base.sku.create');
Route::put('sku', [SkuController::class, 'updateRecord'])->name('base.sku.edit');
Route::delete('sku/{field}', [SkuController::class, 'deleteRecord'])->name('base.sku.delete');
