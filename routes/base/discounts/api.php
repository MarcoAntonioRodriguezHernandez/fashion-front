<?php

use App\Http\Controllers\Base\Discount\DiscountController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for discounts module.
|
*/

Route::post('discounts', [DiscountController::class, 'createRecord'])->name('base.discounts.create');
