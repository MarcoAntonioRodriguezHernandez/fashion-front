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

Route::get('product-image', [ProductImageController::class, 'readAllRecords'])->name('base.product-image.index');
Route::get('product-image/{id}', [ProductImageController::class, 'readRecord'])->name('base.product-image.show');
Route::post('product-image', [ProductImageController::class, 'createRecord'])->name('base.product-image.create');
Route::put('product-image/{id}', [ProductImageController::class, 'updateRecord'])->name('base.product-image.edit');
Route::delete('product-image/{id}', [ProductImageController::class, 'deleteRecord'])->name('base.product-image.delete');
