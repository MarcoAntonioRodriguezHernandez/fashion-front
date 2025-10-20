<?php

use App\Http\Controllers\Base\Tags\TagsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for tags module.
|
*/

Route::post('tags', [TagsController::class, 'createRecord']);
Route::get('tags/{id}', [TagsController::class, 'readRecord']);
Route::get('tags', [TagsController::class, 'readAllRecords']);
Route::put('tags/{id}', [TagsController::class, 'updateRecord']);
Route::delete('tags/{id}', [TagsController::class, 'deleteRecord']);
