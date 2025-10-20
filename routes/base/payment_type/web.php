<?php

use App\Http\Controllers\Base\PaymentType\PaymentTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for payment types.
|
*/

Route::get('payment_type', [PaymentTypeController::class, 'readAllRecords'])->name('base.payment_type.index');
Route::get('payment_type/create', [PaymentTypeController::class, 'createView'])->name('base.payment_type.create.view');
Route::get('payment_type/{id}', [PaymentTypeController::class, 'readRecord'])->name('base.payment_type.show');
Route::get('payment_type/{field}/edit', [PaymentTypeController::class, 'editView'])->name('base.payment_type.edit.view');
Route::post('payment_type', [PaymentTypeController::class, 'createRecord'])->name('base.payment_type.create');
Route::put('payment_type', [PaymentTypeController::class, 'updateRecord'])->name('base.payment_type.edit');
Route::delete('payment_type/{field}', [PaymentTypeController::class, 'deleteRecord'])->name('base.payment_type.delete');
Route::patch('payment_type/{paymentType}/visibility',[PaymentTypeController::class, 'setVisibility'])->name('base.payment_type.set-visibility');
