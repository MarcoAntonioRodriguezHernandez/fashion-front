<?php

use App\Http\Controllers\Base\ProductTag\ProductTagController;
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

Route::get('product-tag', [ProductTagController::class, 'readAllRecords'])->name('base.product_tag.index');
Route::get('product-tag/create', [ProductTagController::class, 'createView'])->name('base.product_tag.create.view');
Route::get('product-tag/{id}', [ProductTagController::class, 'readRecord'])->name('base.product_tag.show');
Route::get('product-tag/{field}/edit', [ProductTagController::class, 'editView'])->name('base.product_tag.edit.view');
Route::post('product-tag', [ProductTagController::class, 'createRecord'])->name('base.product_tag.create');
Route::put('product-tag', [ProductTagController::class, 'updateRecord'])->name('base.product_tag.edit');
Route::delete('product-tag/{field}', [ProductTagController::class, 'deleteRecord'])->name('base.product_tag.delete');
