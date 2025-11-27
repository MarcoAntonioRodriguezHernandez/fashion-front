<?php

namespace App\Http\Controllers\Marketplace\OrderMarketplace;

use App\Enums\{
    CrudAction,
    FoundByMethods,
    Genders,
    ItemConditions,
    NotificationTypes,
    OrderSaleType,
    OrderStatuses,
};
use App\Enums\Auth\{
    ModuleAliases,
    PermissionTypes,
};
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Marketplace\OrderMarketplace\FullPostRequest;
use App\Http\Requests\Marketplace\Order\ItemRequest;
use App\Services\SupplyCreationService;
use App\Traits\Helpers\HandlerFilesTrait;
use App\Models\Base\{
    Category,
    Characteristic,
    Color,
    Designer,
    Discount,
    Event,
    EventType,
    IdentityDocument,
    Item,
    ShippingPrice,
};
use App\Models\Marketplace\{
    OrderMarketplace,
    PaymentOrderMarketplace,
};
use App\Models\User;
use App\Notifications\OrderUpdatedNotification;
use App\Traits\Base\Item\FiltersItems;
use App\Traits\Marketplace\ItemOrder\ManagesItemOrders;
use App\Traits\Marketplace\Order\ManagesOrders;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\{
    Carbon,
    Str,
};
use Illuminate\Support\Facades\{
    Auth,
    DB,
};
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use InvalidArgumentException;
use Nette\NotImplementedException;
use RuntimeException;

class FullOrderMarketplaceController extends GenericCrudProvider
{
    use FiltersItems, ManagesItemOrders, HandlerFilesTrait, ManagesOrders;

    protected string $modelClass = OrderMarketplace::class;

    protected string $indexView = 'marketplace.order_marketplace.show';
    protected SupplyCreationService $supplyCreationService;

    public function __construct(SupplyCreationService $supplyCreationService)
    {
        parent::__construct();

        $this->supplyCreationService = $supplyCreationService;

        $this->middleware('permission:c,' . ModuleAliases::ORDER->value);
    }

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new FullPostRequest())->rules(),
        ];
    }

    public function fullCreateView(ItemRequest $request)
    {
        return $this->processRequest(function () use ($request) {
            $itemResults = Inertia::lazy(function () use ($request) {
                $user = Auth::user();
                $storeId = $user->hasAnyPermission(ModuleAliases::SUPER_ORDER, PermissionTypes::CREATE) ? null : $user->employeeDetail->store_id;

                return $this->filterItemsBy((object) $request->merge([
                    'store' => $storeId,
                ])->all(), 5);
            });
            $clientResults = Inertia::lazy(function () use ($request) {
                return User::select('id', 'name', 'last_name', 'email', 'photo')
                    ->clients()
                    ->where(fn($q) => $q->where('email', 'LIKE', '%' . $request->client_search . '%')
                        ->orWhere(DB::raw("CONCAT(name, ' ', last_name)"), 'LIKE', '%' . $request->client_search . '%'))
                    ->limit(100)
                    ->get();
            });
            $clientInfo = Inertia::lazy(function () use ($request) {
                return User::with('userAddresses')
                    ->find($request->client_id);
            });
            $eventResults = Inertia::lazy(function () use ($request) {
                return Event::where('event_type_id', $request->event_type_id)
                    ->whereDate('scheduled_date', Carbon::parse($request->scheduled_date))
                    ->limit(50)
                    ->get()
                    ->each(fn($e) => $e->variants = Item::find($e->ordersMarketplace()->get()->pluck('itemOrders')->flatten()->pluck('item_id'))->map(fn($i) => $i->product_variant_id));
            });
            $categories = Category::select('id', 'name')->orderBy('name')->get();
            $designers = Designer::select('id', 'name')->orderBy('name')->get();
            $colors = Color::active()->onlyParents()->get()->mapWithKeys(fn($c) => [$c->id => [
                'parent' => $c,
                'children' => $c->children,
            ]]);
            $eventTypes = fn() => EventType::select('id', 'name')->get();
            $foundByMethods = FoundByMethods::getAllNames();
            $genders = Genders::getAllNames();
            $saleTypes = OrderSaleType::getAllNames();
            $shippingPrices = ShippingPrice::select('id', 'name', 'price')->get();
            $characteristics = Characteristic::onlyParents()->with('children')->get()->map(fn($p) => [
                'parent' => $p,
                'children' => $p->children,
            ]);
            $employees = User::with('employeeDetail')->employees()->get()->keyBy('id');

            return Inertia::render('OrderMarketplace/Create', compact('itemResults', 'clientResults', 'clientInfo', 'eventResults', 'categories', 'designers', 'colors', 'characteristics', 'foundByMethods', 'genders', 'saleTypes', 'eventTypes', 'shippingPrices', 'employees'));
        }, '', 'Error while retrieving filter data');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $extraValidation = [
            'employee_id' => Auth::user()->hasAnyPermission(ModuleAliases::SUPER_ORDER, PermissionTypes::CREATE) ?
                'nullable|integer|exists:users,id' :
                'prohibited',
        ];

        $request->validate($extraValidation);

        if (collect($validatedData['items'])->some(fn($i) => $i['sale_type'] == OrderSaleType::RENT->value)) {
            $request->validate((new FullPostRequest())->getEventRules());

            if ($request->has('event')) { // Create event
                $eventData = $request->event;

                if (array_key_exists('event_type_name', $eventData)) { // Create event type
                    $eventTypeId = $event = $this->commitTransaction(fn() => EventType::create([
                        'name' => $eventData['event_type_name'],
                        'slug' => Str::slug($eventData['event_type_name']),
                    ]))->id;
                } else { // Use existing event type
                    $eventTypeId = $eventData['event_type_id'];
                }

                $event = $this->commitTransaction(fn() => Event::create([
                    'event_type_id' => $eventTypeId,
                    'specification' => $eventData['specification'],
                    'scheduled_date' => new DateTime($eventData['scheduled_date']),
                ]));

                $validatedData['event_id'] = $event->id;
            } else {
                $validatedData['event_id'] = $request->event_id;
            }
        }

        $employee = $request->employee_id ? User::find($request->employee_id) : Auth::user();
        $store = $employee->employeeDetail->store;

        return [ // Initialize order data
            'employee_id' => $employee->id,
            'code' => Str::random(10),
            'amount' => 0,
            'discount' => 0,
            'surcharge' => 0,
            'store_id' => $store->id,
            'marketplace_id' => $store->marketplace_id,
            'number_products' => 0,
            'status' => OrderStatuses::WAITING_FOR_COLLECTION->value,
            'date_cancelled' => null,
        ];
    }

    protected function afterCreate(Model $order, Request $request): ?array
    {
        if ($request->filled('shipping')) { // Create shipping
            $order->orderShippingMarketplace()->create([
                'shipping_price_id' => $request->shipping['shipping_price_id'],
                'user_address_id' => $request->shipping['user_address_id'],
                'status' => true,
            ]);
        }

        if ($request->filled('contract_signature')) {
            $identityFolder = 'identity_documents';

            IdentityDocument::create([
                'order_marketplace_id' => $order->id,
                'front' => $this->upload($request->file('identity_document_front'), $identityFolder),
                'back' => $this->upload($request->file('identity_document_back'), $identityFolder),
                'signature' => $request->contract_signature,
            ]);
        }
        Log::info("afterCreate - Processing items");
        $totalPrice = 0;
        $items = Item::find(array_column($request->items, 'item_id'))->keyBy('id'); // Get all items to update status later

        foreach ($request->items as $itemInfo) {
            $item = $items->pull($itemInfo['item_id']);

            if ($item == null)
                throw new InvalidArgumentException('Can not request an item [' . $itemInfo['item_id'] . '] twice in the same order');

            $requestedDates = $itemInfo['sale_type'] == OrderSaleType::RENT->value ? [
                'startDate' => Carbon::parse($itemInfo['rent_detail']['date_start']),
                'endDate' => Carbon::parse($itemInfo['rent_detail']['date_end']),
            ] : null;

            $this->validateItemTransaction($item, $requestedDates);

            $itemOrder = $order->itemOrders()->create([
                'item_id' => $itemInfo['item_id'],
                'additional_notes' => $itemInfo['additional_notes'] ?? null,
                'fitting_price' => $itemInfo['fitting_price'] ?? null,
                'item_price' => 0,
                'fitting_notes' => $itemInfo['fitting_notes'] ?? null,
                'sale_type' => $itemInfo['sale_type'],
                'status' => true,
            ]);

            $itemOrder->created_at = $request->created_at;
            $itemOrder->save();

            if ($requestedDates) { // Create rent details
                Log::info("afterCreate - Creating rent details");
                $itemInfo['rent_detail']['status'] = true;

                $this->updateOrCreateRentDetail($itemInfo['rent_detail'], $itemOrder);
            }

            $itemOrder->update([
                'item_price' => $itemOrder->itemCurrentPrice,
            ]);

            $item->update([
                'condition' => ItemConditions::PRE_LOVED->value,
            ]);

            $totalPrice += $itemOrder->totalPrice;
        }

        if ($request->filled('discount')) {
            $discountValue = $request->discount['value'];

            Discount::create([
                'order_marketplace_id' => $order->id,
                'reason' => $request->discount['reason'],
                'amount' => $request->discount['value'],
            ]);

            if ($totalPrice - $discountValue < 0) {
                throw new RuntimeException('The total price cannot be negative');
            }
        }

        $code = $request->code ?? $order->id;

        $order->update([ // Update order data
            'code' => $code,
            'amount' => $totalPrice,
            'discount' => $discountValue ?? 0,
            'number_products' => count($request->items),
        ]);

        $order->created_at = $request->created_at;
        $order->save();

        $orderNewStatus = OrderStatuses::tryFrom($request->status);
        $duePrice = $this->updateOrderTotalAmount($order);

        if (!$order->created_at->isToday()) {
            $orderNewStatus = OrderStatuses::PAY; // Pay the order if it was created in the past

            $order->update([
                'found_by' => FoundByMethods::EXTEMPORARY->value,
            ]);
        }

        if ($orderNewStatus == OrderStatuses::PAY) {
            $this->createOrderPayments(collect(), $order, $duePrice, 'liverpool');

            $duePrice = 0;

            $order->paymentOrderMarketplace->each(function (PaymentOrderMarketplace $payment) use ($request) {
                $payment->created_at = $request->created_at;
                $payment->save();
            });
        }

        if ($orderNewStatus) {
            $this->updateOrderStatus($order, $orderNewStatus);
        }

        $this->orderNotificationCreated($order);

        $this->supplyCreationService->createAutomaticSupply($order);
        
        return [$order->id];
    }

    protected function orderNotificationCreated(Model $order)
    {
        if (! in_array($order->marketplace->slug, config('common.conspiracy_marketplaces')))
            return;

        $order->client->notify(new OrderUpdatedNotification($order));

        $employeesToNotify = User::query()
            ->notifiedFor(NotificationTypes::ORDER_STATUS)
            ->get();

        foreach ($employeesToNotify as $employee) {
            $employee->notify(new OrderUpdatedNotification($order));
        }
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
            'store_id' => $item->store_id,
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
            'status' => $item->status,
        ];
    }

    protected function readRecord($field)
    {
        throw new NotImplementedException('Use ' . OrderMarketplaceController::class . ' for this operation');
    }

    protected function readAllRecords(Request $request)
    {
        throw new NotImplementedException('Use ' . OrderMarketplaceController::class . ' for this operation');
    }

    protected function updateRecord(Request $request)
    {
        throw new NotImplementedException('Use ' . OrderMarketplaceController::class . ' for this operation');
    }

    protected function deleteRecord($field)
    {
        throw new NotImplementedException('Use ' . OrderMarketplaceController::class . ' for this operation');
    }

    /**
     * Get the dimensions of the image file, the firs element is the width and the second element is the height.
     * 
     * @return array The dimensions of the image
     */
    public function getImageDimensions(): array
    {
        return [false, false];
    }
}
