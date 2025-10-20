<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/home', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/home/stock', [HomeController::class, 'stockTable'])->name('dashboard.stock');
Route::post('/session-put', [HomeController::class, 'sessionPut'])->name('session.put');
Route::get('/products/{product}/variants', [HomeController::class, 'productVariantsPartial']);
