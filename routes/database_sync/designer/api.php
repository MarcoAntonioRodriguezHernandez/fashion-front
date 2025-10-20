<?php

use App\Http\Controllers\DatabaseSync\DesignerSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Designer Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Designer Syncing routes for your application.
|
*/

Route::get('/designer', [DesignerSyncController::class, 'syncAllData']);