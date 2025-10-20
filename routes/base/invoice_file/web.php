<?php

use App\Http\Controllers\Base\InvoiceFile\InvoiceFileController;
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

Route::get('invoice-file', [InvoiceFileController::class, 'readAllRecords'])->name('base.invoice_file.index');
Route::get('invoice-file/create', [InvoiceFileController::class, 'createView'])->name('base.invoice_file.create.view');
Route::get('invoice-file/{id}', [InvoiceFileController::class, 'readRecord'])->name('base.invoice_file.show');
Route::get('invoice-file/{field}/edit', [InvoiceFileController::class, 'editView'])->name('base.invoice_file.edit.view');
Route::post('invoice-file', [InvoiceFileController::class, 'createRecord'])->name('base.invoice_file.create');
Route::put('invoice-file', [InvoiceFileController::class, 'updateRecord'])->name('base.invoice_file.edit');
Route::delete('invoice-file/{field}', [InvoiceFileController::class, 'deleteRecord'])->name('base.invoice_file.delete');
