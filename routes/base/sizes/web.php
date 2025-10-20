<?php

use App\Http\Controllers\Base\Sizes\SizesController;
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

Route::get('sizes', [SizesController::class, 'readAllRecords'])->name('base.sizes.index');
Route::get('sizes/create', [SizesController::class, 'createView'])->name('base.sizes.create.view');
Route::get('sizes/{id}', [SizesController::class, 'readRecord'])->name('base.sizes.show');
Route::get('sizes/{field}/edit', [SizesController::class, 'editView'])->name('base.sizes.edit.view');
Route::post('sizes', [SizesController::class, 'createRecord'])->name('base.sizes.create');
Route::put('sizes', [SizesController::class, 'updateRecord'])->name('base.sizes.edit');
Route::delete('sizes/{field}', [SizesController::class, 'deleteRecord'])->name('base.sizes.delete');
