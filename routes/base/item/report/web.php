<?php

use App\Http\Controllers\Base\Item\ItemReportController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'item/inventory', [ItemReportController::class, 'inventoryView'])->name('base.item.inventory.view');
Route::post('item/report/generate-excel', [ItemReportController::class, 'inventoryReportExcel'])->name('base.item.inventory.report.generate_excel');
