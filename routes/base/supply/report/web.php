<?php

use App\Http\Controllers\Base\Supply\SupplyReportController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'supply/filter/view', [SupplyReportController::class, 'filterView'])->name('base.supply.filter.view');
Route::post('supply/report/generate', [SupplyReportController::class, 'reportDocument'])->name('base.supply.filter.report.generate');
