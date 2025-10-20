<?php

use App\Http\Controllers\Base\Discount\DiscountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for discounts module.
|
*/

Route::post('discounts', [DiscountController::class, 'createRecord'])->name('base.discounts.create');
Route::delete('discounts/{field}', [DiscountController::class, 'deleteRecord'])->name('base.discount.delete');
