<?php

use App\Http\Controllers\Base\Designers\DesignersController;
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

Route::get('designers', [DesignersController::class, 'readAllRecords'])->name('base.designers.index');
Route::get('designers/create', [DesignersController::class, 'createView'])->name('base.designers.create.view');
Route::get('designers/{id}', [DesignersController::class, 'readRecord'])->name('base.designers.show');
Route::get('designers/{field}/edit', [DesignersController::class, 'editView'])->name('base.designers.edit.view');
Route::post('designers', [DesignersController::class, 'createRecord'])->name('base.designers.create');
Route::put('designers', [DesignersController::class, 'updateRecord'])->name('base.designers.edit');
Route::delete('designers/{field}', [DesignersController::class, 'deleteRecord'])->name('base.designers.delete');
