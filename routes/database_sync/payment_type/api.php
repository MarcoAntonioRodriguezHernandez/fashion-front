<?php

use App\Http\Controllers\DatabaseSync\PaymentTypeSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Payment Type Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Payment Type Syncing routes for your application.
|
*/

Route::get('/payment-type', [PaymentTypeSyncController::class, 'syncAllData']);
