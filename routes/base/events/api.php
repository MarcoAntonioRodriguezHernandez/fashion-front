<?php

use App\Http\Controllers\Base\Event\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for events module.
|
*/

Route::get('events', [EventController::class, 'readAllRecords']);
