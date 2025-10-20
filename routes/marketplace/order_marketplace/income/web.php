<?php

use App\Http\Controllers\Marketplace\OrderMarketplace\OrderMarketplaceIncomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for order marketplace cancellations module.
|
*/

Route::post('order-marketplace/income/generate-excel', [OrderMarketplaceIncomeController::class, 'incomeReportExcel'])->name('marketplace.order_marketplace.income.generate_excel');
Route::match(['get', 'post'], 'order-marketplace/income', [OrderMarketplaceIncomeController::class, 'filterView'])->name('marketplace.order_marketplace.income.view');
Route::get('order-marketplace/income/{path}', [OrderMarketplaceIncomeController::class, 'resource'])->name('marketplace.order_marketplace.income.resource')->where('path', '(.+\\/)?(income|daily)-report-\\d{4}-\\d{2}-\\d{2}_\\d{14}\\.xlsx');
