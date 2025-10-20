<?php

use App\Http\Controllers\Base\Invoice\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Invoice module.
|
*/

Route::post('invoice', [InvoiceController::class, 'createRecord']);
Route::get('invoice/{id}', [InvoiceController::class, 'readRecord']);
Route::get('invoice', [InvoiceController::class, 'readAllRecords']);
Route::put('invoice/{id}', [InvoiceController::class, 'updateRecord']);
Route::delete('invoice/{id}', [InvoiceController::class, 'deleteRecord']);

