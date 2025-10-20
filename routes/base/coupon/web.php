<?php

use App\Http\Controllers\Base\Coupon\CouponController;
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

Route::get('coupons', [CouponController::class, 'readAllRecords'])->name('base.coupon.index');
Route::get('coupons/create', [CouponController::class, 'createView'])->name('base.coupon.create.view');
Route::get('coupons/{id}', [CouponController::class, 'readRecord'])->name('base.coupon.show');
Route::get('coupons/{field}/edit', [CouponController::class, 'editView'])->name('base.coupon.edit.view');
Route::post('coupons', [CouponController::class, 'createRecord'])->name('base.coupon.create');
Route::put('coupons', [CouponController::class, 'updateRecord'])->name('base.coupon.edit');
Route::delete('coupons/{field}', [CouponController::class, 'deleteRecord'])->name('base.coupon.delete');
