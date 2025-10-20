<?php

use App\Http\Controllers\DatabaseSync\PricingSchemeSyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Conspiracy PricingScheme Syncing Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Conspiracy PricingScheme Syncing routes for your application.
|
*/

Route::get('/pricing-scheme', [PricingSchemeSyncController::class, 'syncAllData']);
