<?php

use App\Http\Controllers\Base\Invoice\InvoiceController;
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

Route::get('invoice', [InvoiceController::class, 'readAllRecords'])->name('base.invoice.index');
Route::get('invoice/create', [InvoiceController::class, 'createView'])->name('base.invoice.create.view');
Route::get('invoice/{id}', [InvoiceController::class, 'readRecord'])->name('base.invoice.show');
Route::get('invoice/{field}/edit', [InvoiceController::class, 'editView'])->name('base.invoice.edit.view');
Route::post('invoice', [InvoiceController::class, 'createRecord'])->name('base.invoice.create');
Route::put('invoice', [InvoiceController::class, 'updateRecord'])->name('base.invoice.edit');
Route::delete('invoice/{field}', [InvoiceController::class, 'deleteRecord'])->name('base.invoice.delete');

Route::get('/invoice/{invoice_number}/number', [InvoiceController::class, 'getInvoiceByNumber'])->name('base.invoice.by_number');
