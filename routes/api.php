<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-orders/{itemId}', function($itemId) {
    $item = \App\Models\Base\Item::with(['itemOrderMarketplaces.orderMarketplace'])
              ->findOrFail($itemId);
    
    $detailedOrders = $item->itemOrderMarketplaces->map(function ($itemOrder) {
        return [
            'item_order_id' => $itemOrder->id,
            'order_marketplace_id' => $itemOrder->order_marketplace_id,
            'order_exists' => !is_null($itemOrder->orderMarketplace),
            'order_code' => optional($itemOrder->orderMarketplace)->order_code,
            'date' => optional($itemOrder->orderMarketplace)->created_at,
        ];
    });
    
    return response()->json([
        'orders' => $detailedOrders,
        'debug' => [
            'item_id' => $item->id,
            'total_relations' => $item->itemOrderMarketplaces->count(),
            'valid_orders' => $item->itemOrderMarketplaces->whereNotNull('orderMarketplace')->count(),
            'has_orders' => $item->itemOrderMarketplaces->isNotEmpty(),
        ]
    ]);
});