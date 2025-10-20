<?php

use App\Http\Controllers\Base\TemporaryInvitation\TemporaryInvitationController;
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
Route::get('link/create', [TemporaryInvitationController::class, 'showCreateLinkForm'])->name('base.temporary.create');

Route::post('/generate-invitation', [TemporaryInvitationController::class, 'generateLink'])->name('base.temporary.generate_link');

Route::get('/links/show', [TemporaryInvitationController::class, 'showLink'])->name('base.temporary.show');
