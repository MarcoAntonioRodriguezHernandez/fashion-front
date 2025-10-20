<?php

use App\Http\Controllers\Base\ProductImage\ProductImageController;
use App\Http\Controllers\Examples\SampleCrudController;
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

Route::get('product-image', [ProductImageController::class, 'readAllRecords'])->name('base.product_image.index');
Route::get('product-image/create', [ProductImageController::class, 'createView'])->name('base.product_image.create.view');
Route::get('product-image/{id}', [ProductImageController::class, 'readRecord'])->name('base.product_image.show');
Route::get('product-image/{field}/edit', [ProductImageController::class, 'editView'])->name('base.product_image.edit.view');
Route::post('product-image', [ProductImageController::class, 'createRecord'])->name('base.product_image.create');
Route::put('product-image', [ProductImageController::class, 'updateRecord'])->name('base.product_image.edit');
Route::delete('product-image/{field}', [ProductImageController::class, 'deleteRecord'])->name('base.product_image.delete');
