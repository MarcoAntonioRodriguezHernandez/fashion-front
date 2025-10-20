<?php

use App\Http\Controllers\Base\Item\{
    ItemController,
    StockController,
};
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

Route::get('item', [ItemController::class, 'readAllRecords'])->name('base.item.index');
Route::match(['get', 'post'], 'item/filter', [ItemController::class, 'filterView'])->name('base.item.filter.view');

Route::put('item/data/{itemId}', [ItemController::class, 'updateItemData'])->name('base.item.data.update');
Route::get('item/create', [ItemController::class, 'createView'])->name('base.item.create.view');
Route::get('item/{id}', [ItemController::class, 'readRecord'])->name('base.item.show');
Route::get('item/{field}/edit', [ItemController::class, 'editView'])->name('base.item.edit.view');
Route::post('item', [ItemController::class, 'createRecord'])->name('base.item.create');
Route::put('item', [ItemController::class, 'updateRecord'])->name('base.item.edit');
Route::delete('item/{field}', [ItemController::class, 'deleteRecord'])->name('base.item.delete');

Route::get('stock/add', [StockController::class, 'addStockView'])->name('base.stock.add.view');
Route::post('stock/add', [StockController::class, 'addStock'])->name('base.stock.add');

Route::get('item/card/print/{field?}', [ItemController::class, 'cardsPrint'])->name('base.item.card.print');
Route::get('item/card/download/{token}', [ItemController::class, 'cardsDownload'])->name('base.item.card.download');

Route::post('item/mass/condition', [ItemController::class, 'massUpdateCondition'])->name('base.item.mass.condition');
Route::post('item/mass/price-sale', [ItemController::class, 'massUpdatePriceSale'])->name('base.item.mass.price_sale');
Route::post('item/mass/condition-status', [ItemController::class, 'massUpdateConditionStatus'])->name('base.item.mass.condition_status');
Route::post('/item/can-bazar-mass', [ItemController::class, 'canBazarForMass'])->name('item.canBazarForMass');
