<?php

use App\Http\Controllers\DatabaseSync\UserSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy User Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy User Syncing routes for your application.
|
*/

Route::get('/user', [UserSyncController::class, 'syncAllData']);
