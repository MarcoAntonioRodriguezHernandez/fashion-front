<?php

use App\Http\Controllers\Base\Provider\ProviderController;
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

Route::get('providers', [ProviderController::class, 'readAllRecords'])->name('base.provider.index');
Route::get('providers/create', [ProviderController::class, 'createView'])->name('base.provider.create.view');
Route::get('providers/{id}', [ProviderController::class, 'readRecord'])->name('base.provider.show');
Route::get('providers/{field}/edit', [ProviderController::class, 'editView'])->name('base.provider.edit.view');
Route::post('providers', [ProviderController::class, 'createRecord'])->name('base.provider.create');
Route::put('providers', [ProviderController::class, 'updateRecord'])->name('base.provider.edit');
Route::delete('providers/{field}', [ProviderController::class, 'deleteRecord'])->name('base.provider.delete');
