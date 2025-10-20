<?php

use App\Http\Controllers\Base\Provider\ProviderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Providers module.
|
*/

Route::post('providers', [ProviderController::class, 'createRecord']);
Route::get('providers/{id}', [ProviderController::class, 'readRecord']);
Route::get('providers', [ProviderController::class, 'readAllRecords']);
Route::put('providers/{id}', [ProviderController::class, 'updateRecord']);
Route::delete('providers/{id}', [ProviderController::class, 'deleteRecord']);
