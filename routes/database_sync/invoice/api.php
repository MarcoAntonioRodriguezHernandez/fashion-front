<?php

use App\Http\Controllers\DatabaseSync\InvoiceSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Invoice Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Designer Syncing routes for your application.
|
*/

Route::get('/invoice', [InvoiceSyncController::class, 'syncAllData']);