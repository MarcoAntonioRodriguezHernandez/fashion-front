<?php

use App\Http\Controllers\Base\EventType\EventTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for Event Types module.
|
*/

Route::get('event-types', [EventTypeController::class, 'readAllRecords'])->name('base.event_types.index');
Route::get('event-types/create', [EventTypeController::class, 'createView'])->name('base.event_types.create.view');
Route::get('event-types/{field}/edit', [EventTypeController::class, 'editView'])->name('base.event_types.edit.view');
Route::post('event-types', [EventTypeController::class, 'createRecord'])->name('base.event_types.create');
Route::put('event-types', [EventTypeController::class, 'updateRecord'])->name('base.event_types.edit');
Route::delete('event-types/{field}', [EventTypeController::class, 'deleteRecord'])->name('base.event_types.delete');
