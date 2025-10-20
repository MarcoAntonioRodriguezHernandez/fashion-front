<?php

use App\Http\Controllers\Base\UserAddress\UserAddressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for events module.
|
*/

Route::post('user-addresses', [UserAddressController::class, 'createRecord']);
Route::put('user-addresses', [UserAddressController::class, 'updateRecord']);
Route::delete('user-addresses/{field}', [UserAddressController::class, 'deleteRecord']);
