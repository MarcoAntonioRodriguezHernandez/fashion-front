<?php

use App\Http\Controllers\Base\Colors\ColorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for tags Colormodule.
|
*/

Route::get('color', [ColorController::class, 'readAllRecords'])->name('base.color.index');
Route::get('color/create', [ColorController::class, 'createView'])->name('base.color.create.view');
Route::get('color/{id}', [ColorController::class, 'readRecord'])->name('base.color.show');
Route::get('color/{field}/edit', [ColorController::class, 'editView'])->name('base.color.edit.view');
Route::post('color', [ColorController::class, 'createRecord'])->name('base.color.create');
Route::put('color', [ColorController::class, 'updateRecord'])->name('base.color.edit');
Route::delete('color/{field}', [ColorController::class, 'deleteRecord'])->name('base.color.delete');
Route::post('color/{field}/reactivate', [ColorController::class, 'reactiveRecord'])->name('base.color.reactive');
