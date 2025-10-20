<?php

use App\Http\Controllers\Base\EventType\EventTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for tags module.
|
*/

Route::post('event-types', [EventTypeController::class, 'createRecord']);
Route::get('event-types', [EventTypeController::class, 'readAllRecords']);
Route::put('event-types/{id}', [EventTypeController::class, 'updateRecord']);
Route::delete('event-types/{id}', [EventTypeController::class, 'deleteRecord']);
