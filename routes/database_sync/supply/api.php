<?php

use App\Http\Controllers\DatabaseSync\SupplySyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Supply Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Supply Syncing routes for your application.
|
*/

Route::get('/supply', [SupplySyncController::class, 'syncAllData']);
