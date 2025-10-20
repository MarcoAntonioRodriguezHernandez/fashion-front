<?php

use App\Http\Controllers\Base\Coupon\CouponController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for Country module.
|
*/

Route::post('coupons', [CouponController::class, 'createRecord']);
Route::get('coupons/{id}', [CouponController::class, 'readRecord']);
Route::get('coupons', [CouponController::class, 'readAllRecords']);
Route::put('coupons/{id}', [CouponController::class, 'updateRecord']);
Route::delete('coupons/{id}', [CouponController::class, 'deleteRecord']);
