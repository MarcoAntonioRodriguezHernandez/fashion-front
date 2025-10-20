<?php

use App\Http\Controllers\DatabaseSync\StoreSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Size Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Size Syncing routes for your application.
|
*/

Route::get('/store', [StoreSyncController::class, 'syncAllData']);