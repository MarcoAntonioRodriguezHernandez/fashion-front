<?php

namespace App\Http\Controllers\Marketplace\ItemOrderMarketplace;

use App\Enums\{
    CrudAction,
    OrderSaleType,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Marketplace\ItemOrderMarketplace\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Category,
    Characteristic,
    Color,
    Designer,
    Item,
};
use App\Models\Marketplace\{
    ItemOrderMarketplace,
    OrderMarketplace,
};
use App\Models\User;
use App\Traits\Base\Item\FiltersItems;
use App\Traits\Marketplace\ItemOrder\ManagesItemOrders;
use App\Traits\Marketplace\Order\ManagesOrders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use RuntimeException;
use App\Enums\Auth\RoleAliases;

class ItemOrderMarketplaceController extends GenericCrudProvider
{
    use FiltersItems, ManagesOrders, ManagesItemOrders;

    protected string $modelClass = ItemOrderMarketplace::class;

    //WEB VIEWS
    protected string $indexView = 'marketplace.order_marketplace.show';
    protected string $createView = 'marketplace.item_order_marketplace.create';
    protected string $editView = 'marketplace.item_order_marketplace.edit';
    protected string $showView = 'marketplace.item_order_marketplace.show';

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $itemOrderMarketplace = ItemOrderMarketplace::all();
        $orderMarketplace = OrderMarketplace::all();
        $items = Item::all();
        $user = User::all();
        return compact('itemOrderMarketplace', 'orderMarketplace', 'user', 'items');
    }

    protected function editView($field)
    {
        return $this->processRequest(function () use ($field) {
            $itemOrderMarketplace = ItemOrderMarketplace::findOrFail($field);
            $itemDetails = [
                'src_image' => $itemOrderMarketplace->item()->first()->productVariant->productImage->src_image,
                'color' => [
                    'name' => $itemOrderMarketplace->item()->first()->variant->color->name,
                    'hexadecimal' => $itemOrderMarketplace->item()->first()->variant->color->hexadecimal,
                ],
                'name' => $itemOrderMarketplace->item()->first()->productVariant->product->name,
                'size' => $itemOrderMarketplace->item()->first()->variant->size->full_name,
                'designer' => $itemOrderMarketplace->item()->first()->productVariant->product->designer->name,
                'barcode' => $itemOrderMarketplace->item()->first()->barcode,
            ];
            $order = $itemOrderMarketplace->orderMarketplace()->first();
            $event = $order->event()->first();
            $itemResults = Inertia::lazy(function () use ($order){
                $user = Auth::user();
                $requestData= request()->all();

                
                 if (!$user->hasAnyRole(RoleAliases::SUPER_ADMIN)) {
                $requestData['store'] = $user->employeeDetail->store_id;
                } else {
                if (!isset($requestData['store'])) {
                    $requestData['store'] = $order->store_id;
                }
                }

                $filteredItems = $this->filterItemsBy((object) $requestData, 5);
                
                $filteredItems->getCollection()->transform(function ($itemData) {
                    $itemId = is_array($itemData) ? $itemData['id'] : $itemData->id;
                    
                    $item = Item::with([
                        'productVariant.product.pricingScheme',
                        'productVariant.product.category',
                        'productVariant.product.designer',
                        'productVariant.productImage',
                        'variant.size',
                        'variant.color',
                        'store'
                    ])->find($itemId);
                    
                    // Use the buildItemData method to format the item data
                    return $this->buildItemData($item);
                });
                
                return $filteredItems;
            });
            $categories = Category::select('id', 'name')->orderBy('name')->get();
            $designers = Designer::select('id', 'name')->orderBy('name')->get();
            $colors = Color::active()->onlyParents()->get()->mapWithKeys(fn($c) => [$c->id => [
                'parent' => $c,
                'children' => $c->children,
            ]]);
            $characteristics = Characteristic::onlyParents()->with('children')->get()->map(fn($p) => [
                'parent' => $p,
                'children' => $p->children,
            ]);

            $itemOrderMarketplace->rent_detail = $itemOrderMarketplace->sale_type == OrderSaleType::RENT->value ? [
                'rent_start' => $itemOrderMarketplace->rentDetailsMarketplace->date_start,
                'rent_end' => $itemOrderMarketplace->rentDetailsMarketplace->date_end,
                'insurance_price' => $itemOrderMarketplace->rentDetailsMarketplace->insurance_price,
                'duration' => $itemOrderMarketplace->rentDetailsMarketplace->date_start->diffInDays($itemOrderMarketplace->rentDetailsMarketplace->date_end) + 1,
            ] : null;

            if ($event != null) {
                $event->variants = Item::find($event->ordersMarketplace()->get()->pluck('itemOrders')->flatten()->pluck('item_id'))->map(fn($i) => $i->product_variant_id);
                $event->label = $event ?
                    $event->eventType->name . ', ' . $event->specification . ' (' . $event->scheduled_date . ')' :
                    'Sin especificar';
            }

            return Inertia::render('ItemOrderMarketplace/Edit', compact('itemOrderMarketplace', 'itemDetails', 'order', 'event', 'itemResults', 'categories', 'designers', 'colors', 'characteristics'));
        }, 'Could not find item order', 'Error while retrieving item order data');
    }

    protected function beforeUpdate(array &$validatedData, Model $itemOrder, Request $request): ?array
    {

        $item = Item::findOrFail($request->item_id);

        $requestedDates = $itemOrder['sale_type'] == OrderSaleType::RENT->value ? [
            'startDate' => Carbon::parse($validatedData['rent_detail']['date_start']),
            'endDate' => Carbon::parse($validatedData['rent_detail']['date_end']),
        ] : null;

        $omitRent = $request->item_id == $itemOrder->item_id ? $itemOrder->rentDetailsMarketplace : null;

        $this->validateItemTransaction($item, $requestedDates, $omitRent);

        $newItem = Item::find($request->item_id);
        $newItemData = $this->buildItemData($newItem);
        
        if ($request->sale_type == 2) { 
            $rentDuration = $request->rent_detail['date_start'] && $request->rent_detail['date_end'] 
                ? (new \DateTime($request->rent_detail['date_end']))->diff(new \DateTime($request->rent_detail['date_start']))->days + 1
                : 4;
            $basePrice = $newItemData['prices_rent'][$rentDuration] ?? 0;
        } else { 
            $basePrice = $newItemData['price_sale'] ?? 0;
        }
        
        $validatedData['item_price'] = $basePrice;

        return null;
    }

    protected function afterUpdate(Model $itemOrder, Request $request): ?array
    {
        $isRent = $request->sale_type == OrderSaleType::RENT->value;

        if ($isRent) {
            $this->updateOrCreateRentDetail($request->rent_detail, $itemOrder);
        }

        $this->updateOrderTotalAmount($itemOrder->orderMarketplace);

        return [$itemOrder->order_marketplace_id];
    }

    protected function buildItemData(Item $item)
    {
        $productVariant = $item->productVariant;
        $product = $item->product;
        $pricingScheme = $product->pricingScheme;

        return [
            'id' => $item->id,
            'product_variant_id' => $productVariant->id,
            'product_name' => $product->name,
            'product_full_name' => $product->full_name,
            'category_name' => $product->category->name,
            'barcode' => $item->barcode,
            'first_image' => $productVariant->productImage?->src_image ?? asset('media/misc/image.png'),
            'designer_name' => $product->designer->name,
            'store_name' => $item->store->name,
            'size_name' => $item->variant->size->full_name,
            'color_name' => $item->variant->color->name,
            'price_rent_label' => '$ ' . $pricingScheme->sku_4->price . ' / $ ' . $pricingScheme->sku_8->price,
            'prices_rent' => [
                '4' => $pricingScheme->sku_4->price,
                '8' => $pricingScheme->sku_8->price,
            ],
            'price_sale' => $item->price_sale,
            'reserved_dates' => $this->getReservedDates($item),
        ];
    }
}
