<?php

use App\Http\Controllers\Base\Item\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for items module.
|
*/

Route::post('item', [ItemController::class, 'createRecord']);
Route::get('item/{id}', [ItemController::class, 'readRecord']);
Route::get('item', [ItemController::class, 'readAllRecords']);
Route::put('item/{id}', [ItemController::class, 'updateRecord']);
Route::delete('item/{id}', [ItemController::class, 'deleteRecord']);
