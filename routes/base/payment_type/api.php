<?php

use App\Http\Controllers\Base\PaymentType\PaymentTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for payment types.
|
*/

Route::post('payment_type', [PaymentTypeController::class, 'createRecord']);
Route::get('payment_type/{id}', [PaymentTypeController::class, 'readRecord']);
Route::get('payment_type', [PaymentTypeController::class, 'readAllRecords']);
Route::put('payment_type/{id}', [PaymentTypeController::class, 'updateRecord']);
Route::delete('payment_type/{id}', [PaymentTypeController::class, 'deleteRecord']);
