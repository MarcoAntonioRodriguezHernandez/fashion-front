<?php

use App\Http\Controllers\Base\Category\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for category module
|
*/

Route::get('category', [CategoryController::class, 'readAllRecords'])->name('base.category.index');
Route::get('category/create', [CategoryController::class, 'createView'])->name('base.category.create.view');
Route::get('category/{id}', [CategoryController::class, 'readRecord'])->name('base.category.show');
Route::get('category/{field}/edit', [CategoryController::class, 'editView'])->name('base.category.edit.view');
Route::post('category', [CategoryController::class, 'createRecord'])->name('base.category.create');
Route::put('category', [CategoryController::class, 'updateRecord'])->name('base.category.edit');
Route::delete('category/{field}', [CategoryController::class, 'deleteRecord'])->name('base.category.delete');
