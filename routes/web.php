<?php

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

Route::get('/test-crud', function () {
    return view('test-crud.index'); 
});

Route::get('marketplace/order-marketplace/{id}/barcodes', [\App\Http\Controllers\Marketplace\OrderMarketplace\OrderMarketplaceController::class, 'getBarcodes']);