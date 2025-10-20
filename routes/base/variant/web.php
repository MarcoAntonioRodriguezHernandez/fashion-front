<?php

use App\Http\Controllers\Base\Variant\VariantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for variants.
|
*/

Route::get('variant', [VariantController::class, 'readAllRecords'])->name('base.variant.index');
Route::get('variant/create', [VariantController::class, 'createView'])->name('base.variant.create.view');
Route::get('variant/{id}', [VariantController::class, 'readRecord'])->name('base.variant.show');
Route::get('variant/{field}/edit', [VariantController::class, 'editView'])->name('base.variant.edit.view');
Route::post('variant', [VariantController::class, 'createRecord'])->name('base.variant.create');
Route::put('variant', [VariantController::class, 'updateRecord'])->name('base.variant.edit');
Route::delete('variant/{field}', [VariantController::class, 'deleteRecord'])->name('base.variant.delete');
