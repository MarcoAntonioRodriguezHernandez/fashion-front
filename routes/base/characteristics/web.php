<?php

use App\Http\Controllers\Base\Characteristics\CharacteristicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for characteristics module
|
*/

Route::get('characteristics', [CharacteristicController::class, 'readAllRecords'])->name('base.characteristics.index');
Route::get('characteristics/create', [CharacteristicController::class, 'createView'])->name('base.characteristics.create.view');
Route::get('characteristics/{id}', [CharacteristicController::class, 'readRecord'])->name('base.characteristics.show');
Route::get('characteristics/{field}/edit', [CharacteristicController::class, 'editView'])->name('base.characteristics.edit.view');
Route::post('characteristics', [CharacteristicController::class, 'createRecord'])->name('base.characteristics.create');
Route::put('characteristics', [CharacteristicController::class, 'updateRecord'])->name('base.characteristics.edit');
Route::delete('characteristics/{field}', [CharacteristicController::class, 'deleteRecord'])->name('base.characteristics.delete');
