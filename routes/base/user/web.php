<?php

use App\Http\Controllers\Base\Users\UserController;
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

Route::get('users/{userType?}', [UserController::class, 'readAllRecords'])->name('base.user.index')->whereIn('userType', ['employee', 'client']);

Route::get('users/{id}', [UserController::class, 'readRecord'])->name('base.user.show');
Route::get('users/{field}/edit', [UserController::class, 'editView'])->name('base.user.edit.view');

Route::get('users/{id}/edit-roles', [UserController::class, 'editRolesView'])->name('base.user.edit_roles');
Route::put('users/{id}/update-roles', [UserController::class, 'updateRoles'])->name('base.user.update_roles');

Route::get('users/{id}/edit-email', [UserController::class, 'editEmailView'])->name('base.user.edit_email');
Route::put('users/{id}/update-email', [UserController::class, 'updateEmail'])->name('base.user.update_email');

Route::get('users/{id}/edit-password', [UserController::class, 'editPasswordView'])->name('base.user.edit_password');
Route::put('users/{id}/update-password', [UserController::class, 'updatePassword'])->name('base.user.update_password');

Route::get('users/{id}/index-addresses', [UserController::class, 'indexAddressesView'])->name('base.user.addresses');

Route::post('users', [UserController::class, 'createRecord'])->name('base.user.create');
Route::put('users', [UserController::class, 'updateRecord'])->name('base.user.edit');
Route::delete('users/{field}', [UserController::class, 'deleteRecord'])->name('base.user.delete');
