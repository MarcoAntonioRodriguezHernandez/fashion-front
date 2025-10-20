<?php

use App\Http\Controllers\Base\UserAddress\UserAddressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for user_addresses module.
|
*/

Route::get('user/{userId}/addresses/create', [UserAddressController::class, 'createView'])->name('base.user_addresses.create.view');
Route::get('user-addresses/{field}', [UserAddressController::class, 'readRecord'])->name('base.user_addresses.show');
Route::get('user-addresses/{field}/edit', [UserAddressController::class, 'editView'])->name('base.user_addresses.edit.view');

Route::post('user-addresses', [UserAddressController::class, 'createRecord'])->name('base.user_addresses.create');
Route::put('user-addresses', [UserAddressController::class, 'updateRecord'])->name('base.user_addresses.edit');
Route::delete('user-addresses/{field}', [UserAddressController::class, 'deleteRecord'])->name('base.user_addresses.delete');
