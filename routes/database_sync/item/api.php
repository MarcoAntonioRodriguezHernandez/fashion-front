<?php

use App\Http\Controllers\DatabaseSync\ItemSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Item Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Item Syncing routes for your application.
|
*/

Route::get('/item', [ItemSyncController::class, 'syncAllData']);
