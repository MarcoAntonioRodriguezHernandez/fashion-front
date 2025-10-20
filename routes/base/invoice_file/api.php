<?php

use App\Http\Controllers\Base\InvoiceFile\InvoiceFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Invoice File module.
|
*/

Route::post('invoice-file', [InvoiceFileController::class, 'createRecord']);
Route::get('invoice-file/{id}', [InvoiceFileController::class, 'readRecord']);
Route::get('invoice-file', [InvoiceFileController::class, 'readAllRecords']);
Route::put('invoice-file/{id}', [InvoiceFileController::class, 'updateRecord']);
Route::delete('invoice-file/{id}', [InvoiceFileController::class, 'deleteRecord']);
