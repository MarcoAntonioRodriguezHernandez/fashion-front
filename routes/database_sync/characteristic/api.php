<?php

use App\Http\Controllers\DatabaseSync\CharacteristicSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Characteristic Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Characteristic Syncing routes for your application.
|
*/

Route::get('/characteristic', [CharacteristicSyncController::class, 'syncAllData']);
