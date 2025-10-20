<?php

use App\Http\Controllers\DatabaseSync\VariantSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy Variant Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy Variant Syncing routes for your application.
|
*/

Route::get('/variant', [VariantSyncController::class, 'syncAllData']);
