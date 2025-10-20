<?php

use App\Http\Controllers\DatabaseSync\OrderSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Order Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Order Syncing routes for your application.
|
*/

Route::get('/order', [OrderSyncController::class, 'syncAllData']);