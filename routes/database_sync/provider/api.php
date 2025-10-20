<?php

use App\Http\Controllers\DatabaseSync\ProviderSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Size Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Size Syncing routes for your application.
|
*/

Route::get('/provider', [ProviderSyncController::class, 'syncAllData']);
