<?php

use App\Http\Controllers\Base\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Invoice File module.
|
*/


Route::get('notifications/{id}', [NotificationController::class, 'readRecord']);
Route::get('notifications', [NotificationController::class, 'readAllRecords']);