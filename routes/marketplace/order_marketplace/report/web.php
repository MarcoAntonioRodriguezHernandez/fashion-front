<?php

use App\Http\Controllers\Marketplace\OrderMarketplace\OrderMarketplaceReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for order marketplace cancellations module.
|
*/

Route::post('order/report/generate-excel', [OrderMarketplaceReportController::class, 'orderReportExcel'])->name('marketplace.order_marketplace.report.generate_excel');
Route::match(['get', 'post'],'order/marketplace/filter', [OrderMarketplaceReportController::class, 'filterView'])->name('marketplace.order.filter.view');

Route::get('order-marketplace/report/{path}', [OrderMarketplaceReportController::class, 'resource'])->name('marketplace.order_marketplace.resource')->where('path', '(.+\\/)?order-report-\\d{4}-\\d{2}-\\d{2}_\\d{14}\\.xlsx');
