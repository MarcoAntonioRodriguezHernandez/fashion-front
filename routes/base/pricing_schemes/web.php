<?php

use App\Http\Controllers\Base\PricingScheme\PricingShemeController;
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

Route::get('pricing-schemes', [PricingShemeController::class, 'readAllRecords'])->name('base.pricing_schemes.index');
Route::get('pricing-schemes/create/{productId?}', [PricingShemeController::class, 'createView'])->name('base.pricing_schemes.create.view');
Route::get('pricing-schemes/{id}', [PricingShemeController::class, 'readRecord'])->name('base.pricing_schemes.show');
Route::get('pricing-schemes/{field}/edit', [PricingShemeController::class, 'editView'])->name('base.pricing_schemes.edit.view');
Route::post('pricing-schemes', [PricingShemeController::class, 'createRecord'])->name('base.pricing_schemes.create');
Route::put('pricing-schemes', [PricingShemeController::class, 'updateRecord'])->name('base.pricing_schemes.edit');
Route::delete('pricing-schemes/{field}', [PricingShemeController::class, 'deleteRecord'])->name('base.pricing_schemes.delete');
