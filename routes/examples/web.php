<?php

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

Route::get('example', [SampleCrudController::class, 'readAllRecords'])->name('example.index');
Route::get('example/create', [SampleCrudController::class, 'createView'])->name('example.create.view');
Route::get('example/{id}', [SampleCrudController::class, 'readRecord'])->name('example.show');
Route::get('example/{field}/edit', [SampleCrudController::class, 'editView'])->name('example.edit.view');
Route::post('example', [SampleCrudController::class, 'createRecord'])->name('example.create');
Route::put('example', [SampleCrudController::class, 'updateRecord'])->name('example.edit');
Route::delete('example/{field}', [SampleCrudController::class, 'deleteRecord'])->name('example.delete');
