<?php

use App\Http\Controllers\DatabaseSync\ProductSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Product Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Product Syncing routes for your application.
|
*/

Route::get('/product', [ProductSyncController::class, 'syncAllData']);
