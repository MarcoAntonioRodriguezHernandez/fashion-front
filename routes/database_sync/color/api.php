<?php

use App\Http\Controllers\DatabaseSync\ColorSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Color Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Color Syncing routes for your application.
|
*/

Route::get('/color', [ColorSyncController::class, 'syncAllData']);