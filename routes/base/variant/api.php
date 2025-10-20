<?php

use App\Http\Controllers\Base\Variant\VariantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for variants.
|
*/

Route::post('variant', [VariantController::class, 'createRecord']);
Route::get('variant/{id}', [VariantController::class, 'readRecord']);
Route::get('variant', [VariantController::class, 'readAllRecords']);
Route::put('variant/{id}', [VariantController::class, 'updateRecord']);
Route::delete('variant/{id}', [VariantController::class, 'deleteRecord']);
