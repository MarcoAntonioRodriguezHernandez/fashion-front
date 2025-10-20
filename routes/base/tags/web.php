<?php

use App\Http\Controllers\Base\Tags\TagsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for tags module.
|
*/

Route::get('tags', [TagsController::class, 'readAllRecords'])->name('base.tags.index');
Route::get('tags/create', [TagsController::class, 'createView'])->name('base.tags.create.view');
Route::get('tags/{id}', [TagsController::class, 'readRecord'])->name('base.tags.show');
Route::get('tags/{field}/edit', [TagsController::class, 'editView'])->name('base.tags.edit.view');
Route::post('tags', [TagsController::class, 'createRecord'])->name('base.tags.create');
Route::put('tags', [TagsController::class, 'updateRecord'])->name('base.tags.edit');
Route::delete('tags/{field}', [TagsController::class, 'deleteRecord'])->name('base.tags.delete');
