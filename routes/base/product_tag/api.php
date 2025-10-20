<?php

use App\Http\Controllers\Base\ProductTag\ProductTagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for product tags module.
|
*/

Route::post('product-tag', [ProductTagController::class, 'createRecord']);
Route::get('product-tag/{id}', [ProductTagController::class, 'readRecord']);
Route::get('product-tag', [ProductTagController::class, 'readAllRecords']);
Route::put('product-tag/{id}', [ProductTagController::class, 'updateRecord']);
Route::delete('product-tag/{id}', [ProductTagController::class, 'deleteRecord']);
