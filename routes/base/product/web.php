<?php

use App\Http\Controllers\Base\Products\{
    ProductController,
    FullProductController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for product module.
|
*/

Route::get('product', [ProductController::class, 'readAllRecords'])->name('base.product.index');
Route::get('product/full-create', [FullProductController::class, 'createView'])->name('base.product.full_create.view');
Route::get('product/{id}', [ProductController::class, 'readRecord'])->name('base.product.show');
Route::get('product/{field}/edit', [ProductController::class, 'editView'])->name('base.product.edit.view');
Route::get('product/{field}/variants', [ProductController::class, 'variantsView'])->name('base.product.variants.view');
Route::put('product/{field}/variants', [ProductController::class, 'variantsUpdate'])->name('base.product.variants');
Route::delete('product/{field}/variants/{colorId}', [ProductController::class, 'variantsDelete'])->name('base.product.variants.destroy');
Route::post('product/full', [FullProductController::class, 'createRecord'])->name('base.product.full_create');
Route::put('product', [ProductController::class, 'updateRecord'])->name('base.product.edit');
Route::delete('product/{field}', [ProductController::class, 'deleteRecord'])->name('base.product.delete');
Route::post('product/mass/full-price', [ProductController::class, 'massUpdateFullPrice'])->name('base.product.mass.full_price');
