<?php

use App\Http\Controllers\DatabaseSync\CategorySyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Category Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Category Syncing routes for your application.
|
*/

Route::get('/category', [CategorySyncController::class, 'syncAllData']);