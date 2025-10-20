<?php

use App\Http\Controllers\Auth\RoleController;
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

Route::get('roles', [RoleController::class, 'readAllRecords'])->name('auth.roles.index');
Route::get('roles/create', [RoleController::class, 'createView'])->name('auth.roles.create.view');
Route::get('roles/{id}', [RoleController::class, 'readRecord'])->name('auth.roles.show');
Route::get('roles/{id}/edit', [RoleController::class, 'editView'])->name('auth.roles.edit.view');
Route::post('roles', [RoleController::class, 'createRecord'])->name('auth.roles.create');
Route::put('roles', [RoleController::class, 'updateRecord'])->name('auth.roles.edit');
Route::delete('roles/{field}', [RoleController::class, 'deleteRecord'])->name('auth.roles.delete');
