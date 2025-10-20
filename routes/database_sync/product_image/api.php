<?php

use App\Http\Controllers\DatabaseSync\ProductImageSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Image Local Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Image Local Syncing routes for your application.
|
*/

Route::get('/product-image/download', [ProductImageSyncController::class, 'syncLocalData']);
Route::get('/product-image/local-paths', [ProductImageSyncController::class, 'syncLocalPaths']);
