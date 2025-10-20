<?php

use App\Http\Controllers\Base\Notification\NotificationController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('notifications', [NotificationController::class, 'readAllRecords'])->name('base.notifications.index');
Route::get('notifications/notified-users', [NotificationController::class, 'editNotifiedUsersView'])->name('base.notifications.users.view');
Route::post('notifications/{user}/update', [NotificationController::class, 'updateUserNotifications'])->name('base.notifications.users');
Route::post('notifications/user-search', [NotificationController::class, 'searchNonNotifiedUsers'])->name('base.user.search');
Route::get('notifications/{id}', [NotificationController::class, 'readRecord'])->name('base.notifications.show');
