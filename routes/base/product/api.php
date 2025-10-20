<?php

use App\Http\Controllers\Base\Products\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for products module.
|
*/

Route::post('product', [ProductController::class, 'createRecord']);
Route::get('product/{id}', [ProductController::class, 'readRecord']);
Route::get('product', [ProductController::class, 'readAllRecords']);
Route::put('product/{id}', [ProductController::class, 'updateRecord']);
Route::delete('product/{id}', [ProductController::class, 'deleteRecord']);
