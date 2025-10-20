<?php

use App\Http\Controllers\Base\Event\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for events module.
|
*/

Route::get('events', [EventController::class, 'readAllRecords'])->name('base.events.index');
Route::delete('events/{field}', [EventController::class, 'deleteRecord'])->name('base.events.delete');
