<?php

use App\Http\Controllers\Base\PricingScheme\PricingShemeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for examples module.
|
*/

Route::post('pricing-schemes', [PricingShemeController::class, 'createRecord']);
Route::get('pricing-schemes/{id}', [PricingShemeController::class, 'readRecord']);
Route::get('pricing-schemes', [PricingShemeController::class, 'readAllRecords']);
Route::put('pricing-schemes/{id}', [PricingShemeController::class, 'updateRecord']);
Route::delete('pricing-schemes/{id}', [PricingShemeController::class, 'deleteRecord']);
