<?php

namespace App\Http\Controllers\Marketplace\OrderMarketplace;

use App\Enums\{
    CrudAction,
    OrderSaleType,
    OrderStatuses,
    StoreStatuses,
    FoundByMethods,
    ItemStatuses,
    PaymentStatuses,
};
use App\Enums\Auth\{
    ModuleAliases,
    PermissionTypes,
};
use App\Enums\DiscountAppliesTo;
use App\Enums\PaymentOrderMarketplaceReason;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Marketplace\OrderMarketplace\{
    PostRequest,
    PutRequest,
};
use App\Models\Base\{
    Marketplace,
    Store,
    PaymentType,
};
use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use App\Services\Common\Ticket\TicketBuilder;
use App\Traits\Helpers\ResponseTrait;
use App\Traits\Marketplace\ItemOrder\ManagesItemOrders;
use App\Traits\Marketplace\Order\{
    CancelOrder,
    FiltersOrders,
    ManagesOrders,
};
use Exception;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Inertia\Inertia;
use Nette\NotImplementedException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderMarketplaceController extends GenericCrudProvider
{
    use FiltersOrders, CancelOrder, ResponseTrait, ManagesOrders, ManagesItemOrders;

    protected string $modelClass = OrderMarketplace::class;

    //WEB VIEWS
    protected string $indexView = 'marketplace.order_marketplace.index';
    protected string $showView = 'marketplace.order_marketplace.show';
    protected string $createView = 'marketplace.order_marketplace.create';
    protected string $editView = 'marketplace.order_marketplace.edit';

    protected ?string $searchField = 'search';

    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:r,' . ModuleAliases::ORDER->value)->only([
            'readAllRecords',
            'readRecord',
        ]);

        $this->middleware('permission:c,' . ModuleAliases::ORDER->value)->only([
            'createRecord',
            'createView',
        ]);

        $this->middleware('permission:u,' . ModuleAliases::ORDER->value)->only([
            'editView',
            'updateRecord',
            'deleteRecord',
            'cancel',
            'updateStatus',
        ]);
    }

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function createView()
    {
        throw new NotImplementedException('Use ' . FullOrderMarketplaceController::class . ' for this operation');
    }

    protected function beforeReadAll(Builder $query, Request $request): Builder
    {
        $user = Auth::user();

        return $query->latest('created_at')
            ->when(
                !$user->hasAnyPermission(ModuleAliases::SUPER_ORDER, PermissionTypes::READ),
                fn($q) => $q->where('store_id', Auth::user()->employeeDetail->store_id)
            );
    }

    protected function pushCreateView()
    {
        $stores = Store::getByStatus(StoreStatuses::ACTIVE);
        $users = User::all();
        $marketplaces = Marketplace::all();
        return compact('stores', 'users', 'marketplaces');
    }

    protected function pushEditView(Model $model)
    {
        $stores = Store::all();
        $users = User::all();
        $marketplaces = Marketplace::all();
        return compact('stores', 'users', 'marketplaces');
    }

    public function updateStatus(Request $request, $orderId)
    {
        return $this->processRequest(function () use ($request, $orderId) {
            $newStatus = OrderStatuses::from($request->orderStatus);
            $creditAmount = (int) $request->credit_amount;

            if (in_array($newStatus, [OrderStatuses::CANCELLED, OrderStatuses::RETURNED])) {
                $this->cancelOrder($orderId, $creditAmount);

                return $this->makeResponse([
                    'message' => 'Order cancelled successfully',
                    'status' => Response::HTTP_OK,
                ]);
            }

            $orderMarketplace = $this->findOrder($orderId); // Find order non cancelled

            $this->updateOrderStatus($orderMarketplace, $newStatus);

            return $this->makeResponse([
                'message' => $orderMarketplace->wasChanged('surcharge') ?
                    'Los recargos de la orden se han actualizado a un total de $' . $orderMarketplace->surcharge :
                    'Estado de la orden actualizado correctamente a ' . OrderStatuses::getName($newStatus->value),
                'status' => Response::HTTP_OK,
                'data' => [
                    'data' => [
                        'surcharge' => $orderMarketplace->surcharge,
                    ],
                ],
            ]);
        }, 'Order not found', 'Error while changing order status');
    }

    protected function readRecord($id)
    {
        return $this->processRequest(function () use ($id) {
            $orderMarketplace = OrderMarketplace::findOrFail($id);
            $data = $this->getOrderMarketplaceData($orderMarketplace);

            $data['paymentTypes'] = PaymentType::visible()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            $data['paymentStatuses'] = PaymentStatuses::getAllNames();
            $data['orderStatuses'] = $this->getOrderStatuses($orderMarketplace, [
                'total_payment' => $data['payments']->sum('payment'),
            ]);
            $data['paymentReasons'] = PaymentOrderMarketplaceReason::getAllNames();
            $data['discountAppliesTo'] = DiscountAppliesTo::getAllNames();

            return Inertia::render('OrderMarketplace/Show', $data);
        }, 'Could not find order', 'Error while retrieving order data');
    }

    public function getOrderMarketplaceData(OrderMarketplace $orderMarketplace)
    {
        $orderSaleTypes = $orderMarketplace->itemOrders->pluck('sale_type')->unique();
        $orderSaleTypeNames = $orderSaleTypes->map(fn($type) => OrderSaleType::getName($type))->toArray();
        return [
            'order' => [
                'id' => $orderMarketplace->id,
                'code' => $orderMarketplace->code,
                'created_at' => $orderMarketplace->created_at->format('d/m/Y'),
                'hour_created' => $orderMarketplace->created_at->format('H:i'),
                'store' => $orderMarketplace->store->only(['id', 'name']),
                'items_amount' => $orderMarketplace->items->count(),
                'amount' => $orderMarketplace->amount,
                'amount_total' => $orderMarketplace->amount_total,
                'surcharge' => $orderMarketplace->surcharge,
                'is_active' => $orderMarketplace->is_active,
                'is_enabled' => $orderMarketplace->is_enabled,
                'sale_type_names' => $orderSaleTypeNames,
                'total_payment' => $orderMarketplace->paymentOrderMarketplace()
                    ->where('status', PaymentStatuses::APPROVED)
                    ->sum('payment'),
                'discount' => $orderMarketplace->discount,
                'found_by' => FoundByMethods::getName($orderMarketplace->found_by),
                'event_label' => $orderMarketplace->event ?
                    $orderMarketplace->event->eventType->name . ', ' . $orderMarketplace->event->specification . ' (' . $orderMarketplace->event->scheduled_date . ')' :
                    'Sin especificar',
                'status' => [
                    'value' => $orderMarketplace->status,
                    'name' => OrderStatuses::getName($orderMarketplace->status),
                    'color' => OrderStatuses::getColor($orderMarketplace->status),
                ],
                'identity_document' => $orderMarketplace->identityDocument,
                'advance_payment' => $orderMarketplace->advance_payment,
            ],
            'items' => $orderMarketplace->itemOrders()->with(['item', 'rentDetailsMarketplace'])->orderBy('status', 'desc')->get()->map(function ($itemOrder) {
                $item = $itemOrder->item;
                $pricingScheme = $item->product->pricingScheme;

                return [
                    'id' => $item->id,
                    'barcode' => $item->barcode,
                    'first_image' => $item->productVariant->productImage?->src_image ?? asset('media/misc/image.png'),
                    'product_name' => $item->product->name,
                    'product_full_name' => $item->product->full_name,
                    'designer_name' => $item->product->designer->name,
                    'size_name' => $item->variant->size->full_name,
                    'store_name' => $item->store->name,
                    'color_name' => $item->variant->color?->name,
                    'sale_type_name' => OrderSaleType::getName($itemOrder->sale_type),
                    'price' => $itemOrder->totalPrice,
                    'prices_rent' => [
                        '4' => $pricingScheme->sku_4->price,
                        '8' => $pricingScheme->sku_8->price,
                    ],
                    'price_sale' => $item->price_sale,
                    'item_order_status' => $itemOrder->status,
                    'reserved_dates' => $this->getReservedDates($item, $itemOrder->rentDetailsMarketplace),
                    'order_detail' => [
                        'id' => $itemOrder->id,
                        'sale_type' => $itemOrder->sale_type,
                        'additional_notes' => $itemOrder->additional_notes,
                        'fitting_price' => $itemOrder->fitting_price,
                        'fitting_notes' => $itemOrder->fitting_notes,
                        'rent_detail' => $itemOrder->sale_type == OrderSaleType::RENT->value ? [
                            'rent_start' => $itemOrder->rentDetailsMarketplace->date_start,
                            'rent_end' => $itemOrder->rentDetailsMarketplace->date_end,
                            'insurance_price' => $itemOrder->rentDetailsMarketplace->insurance_price,
                            'duration' => $itemOrder->rentDetailsMarketplace->date_start->diffInDays($itemOrder->rentDetailsMarketplace->date_end) + 1,
                        ] : null,
                    ],
                ];
            }),
            'payments' => $orderMarketplace->paymentOrderMarketplace->map(fn($payment) => [
                'id' => $payment->id,
                'total' => $payment->total,
                'payment' => $payment->payment,
                'to_credit' => $payment->to_credit,
                'date' => date('d / m / Y', $payment->created_at->timestamp),
                'payment_type' => $payment->paymentType->name,
                'status_name' => $payment->status_name,
                'status' => $payment->status,
                'reason' => $payment->reason,
                'reason_name' => PaymentOrderMarketplaceReason::getName($payment->reason),
                'note_reason' => $payment->note_reason,
            ]),
            'discounts' => $orderMarketplace->discounts->map(fn($discount) => [
                'id' => $discount->id,
                'amount' => $discount->amount,
                'reason' => $discount->reason,
                'applies_to' => $discount->applies_to,
                'applies_to_name' => DiscountAppliesTo::getName($discount->applies_to),
            ]),
            'client' => [
                'id' => $orderMarketplace->client->id,
                'full_name' => $orderMarketplace->client->full_name,
                'photo' => $orderMarketplace->client->photo ?? asset('media/avatars/blank.png'),
                'gender' => $orderMarketplace->client->clientDetail->gender_name,
                'email' => $orderMarketplace->client->email,
                'phone' => $orderMarketplace->client->phone ?? 'Desconocido',
            ],
            'employee' => [
                'id' => $orderMarketplace->employee->id,
                'full_name' => $orderMarketplace->employee->full_name,
                'photo' => $orderMarketplace->employee->photo ?? asset('media/avatars/blank.png'),
                'email' => $orderMarketplace->employee->email,
                'phone' => $orderMarketplace->employee->employeeDetail->phone ?? 'Desconocido',
            ],
            'shipping' => $orderMarketplace->orderShippingMarketplace()->with('userAddress', 'shippingPrice')->first(),
        ];
    }

    public function showDocumentation($id, string $type)
    {
        return $this->processRequest(function () use ($id, $type) {
            $orderMarketplace = OrderMarketplace::findOrFail($id);

            $builder = match ($type) {
                'contract' => TicketBuilder::make($orderMarketplace->client, 'tickets.contract')
                    ->fileName(strtolower('Contrato-' . $orderMarketplace->code))
                    ->title(config('app.name'))
                    ->addStyleSheet(public_path('assets/css/contract.css'))
                    ->data([
                        'client' => $orderMarketplace->client,
                    ]),
                'promissory' => TicketBuilder::make($orderMarketplace->client, 'tickets.promissory')
                    ->fileName(strtolower('Pagare-' . $orderMarketplace->code))
                    ->title('Pagaré')
                    ->addStyleSheet(public_path('assets/css/promissory.css'))
                    ->data([
                        'data' => (object) [
                            'amountTotal' => $orderMarketplace->amount_total,
                            'deadline' => $orderMarketplace->itemOrders()->where('sale_type', OrderSaleType::RENT)->first()->rentDetailsMarketplace->date_end->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'currentDate' => now()->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                        ]
                    ]),
                'note' => TicketBuilder::make($orderMarketplace->client, 'tickets.note')
                    ->fileName(strtolower('Contrato-' . $orderMarketplace->code))
                    ->title(config('app.name'))
                    ->addStyleSheet(public_path('assets/css/note.css'))
                    ->data([
                        'data' => (object) [
                            'currentDate' => now()->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'orderCode' => $orderMarketplace->code,
                            'full_name' => $orderMarketplace->employee->full_name,
                            'rentDate' => $orderMarketplace->itemOrders->first()->rentDetailsMarketplace->date_start->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'itemOrders' => $orderMarketplace->itemOrders()->where('status', true)->get()->map(function ($itemOrder) {
                                return [
                                    'product_full_name' => $itemOrder->item->product->full_name,
                                    'size' => $itemOrder->item->variant->size->full_name,
                                    'color' => $itemOrder->item->variant->color?->name,
                                    'date' => $itemOrder->rentDetailsMarketplace->date_start->format('d/m/Y'),
                                ];
                            }),
                            'totals' => (object) [
                                'sale_type' => $orderMarketplace->itemOrders()->get()->sum(function ($itemOrder) {
                                    return $itemOrder->sale_type == OrderSaleType::RENT ? 0 : $itemOrder->totalPrice;
                                }),
                                'rent_price' => number_format($orderMarketplace->itemOrders()->get()->sum(function ($itemOrder) {
                                    $rentDetails = $itemOrder->rentDetailsMarketplace;
                                    $pricingScheme = $itemOrder->item->product->pricingScheme;
                                    $baseRentPrice = ($rentDetails->date_start->diffInDays($rentDetails->date_end) + 1 == 4)
                                        ? $pricingScheme->sku_4->price
                                        : $pricingScheme->sku_8->price;

                                    return $baseRentPrice;
                                }), 2, '.', ','),
                                'employee' => $orderMarketplace->employee->full_name,
                                'insurance' => number_format($orderMarketplace->itemOrders()->get()->sum(function ($itemOrder) {
                                    return $itemOrder->rentDetailsMarketplace->insurance_price ?? 0.00;
                                }), 2, '.', ','),
                                'total' => number_format($orderMarketplace->amount_total, 2, '.', ','),
                                'advance_payment' => number_format($orderMarketplace->advance_payment, 2, '.', ','),
                                'pending' => number_format($orderMarketplace->pending, 2, '.', ','),
                            ],
                        ]
                    ]),
                default => throw new NotImplementedException('Document type not implemented'),
            };

            return Response::make($builder->toPdfContent(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }, 'Could not find order', 'Error while retrieving order data');
    }

    public function showDocumentationSale($id, string $type)
    {
        return $this->processRequest(function () use ($id, $type) {
            $orderMarketplace = OrderMarketplace::findOrFail($id);

            $builder = match ($type) {
                'noteSale' => TicketBuilder::make($orderMarketplace->client, 'tickets.noteSale')
                    ->fileName(strtolower('Contrato-' . $orderMarketplace->code))
                    ->title(config('app.name'))
                    ->addStyleSheet(public_path('assets/css/note.css'))
                    ->data([
                        'data' => (object) [
                            'currentDate' => now()->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'orderCode' => $orderMarketplace->code,
                            'full_name' => $orderMarketplace->employee->full_name,
                            'saleDate' => $orderMarketplace->itemOrders->first()->created_at->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'itemOrders' => $orderMarketplace->itemOrders()->where('status', true)->get()->map(function ($itemOrder) {
                                return [
                                    'product_full_name' => $itemOrder->item->product->full_name,
                                    'size' => $itemOrder->item->variant->size->full_name,
                                    'color' => $itemOrder->item->variant->color?->name,
                                    'date' => $itemOrder->created_at->format('d/m/Y'),
                                ];
                            }),
                            'totals' => (object) [
                                'sale_type' => $orderMarketplace->itemOrders()->get()->sum(function ($itemOrder) {
                                    return $itemOrder->sale_type == OrderSaleType::SALE ? 0 : $itemOrder->totalPrice;
                                }),
                                'sale_price' => number_format($orderMarketplace->itemOrders()->get()->sum(function ($itemOrder) {
                                    $pricingScheme = $itemOrder->item->product->pricingScheme;
                                    $baseSalePrice = ($itemOrder->sale_type == OrderSaleType::SALE);
                                    return $baseSalePrice;
                                }), 2, '.', ','),
                                'employee' => $orderMarketplace->employee->full_name,
                                'total' => number_format($orderMarketplace->amount_total, 2, '.', ','),
                                'advance_payment' => number_format($orderMarketplace->advance_payment, 2, '.', ','),
                                'pending' => number_format($orderMarketplace->pending, 2, '.', ','),
                            ],
                        ]
                    ]),
                default => throw new NotImplementedException('Document type not implemented'),
            };
            return Response::make($builder->toPdfContent(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }, 'Could not find order', 'Error while retrieving order data');
    }

    public function showDocumentationSaleRent($id, string $type)
    {
        return $this->processRequest(function () use ($id, $type) {
            $orderMarketplace = OrderMarketplace::findOrFail($id);

            $builder = match ($type) {
                'contract' => TicketBuilder::make($orderMarketplace->client, 'tickets.contract')
                    ->fileName(strtolower('Contrato-' . $orderMarketplace->code))
                    ->title(config('app.name'))
                    ->addStyleSheet(public_path('assets/css/contract.css'))
                    ->data([
                        'client' => $orderMarketplace->client,
                    ]),
                'promissory' => TicketBuilder::make($orderMarketplace->client, 'tickets.promissory')
                    ->fileName(strtolower('Pagare-' . $orderMarketplace->code))
                    ->title('Pagaré')
                    ->addStyleSheet(public_path('assets/css/promissory.css'))
                    ->data([
                        'data' => (object) [
                            'amountTotal' => $orderMarketplace->amount_total,
                            'deadline' => $orderMarketplace->itemOrders()
                                ->where('sale_type', OrderSaleType::RENT)
                                ->first()
                                ->rentDetailsMarketplace
                                ->date_end
                                ->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'currentDate' => now()->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                        ]
                    ]),
                'noteSaleRent' => TicketBuilder::make($orderMarketplace->client, 'tickets.noteSaleRent')
                    ->fileName(strtolower('Contrato-' . $orderMarketplace->code))
                    ->title(config('app.name'))
                    ->addStyleSheet(public_path('assets/css/note.css'))
                    ->data([
                        'data' => (object) [
                            'currentDate' => now()->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'orderCode' => $orderMarketplace->code,
                            'full_name' => $orderMarketplace->employee->full_name,
                            'saleDate' => $orderMarketplace->itemOrders->first()->created_at->translatedFormat('d \\d\\e F \\d\\e\\l \\a\\ñ\\o Y'),
                            'itemOrders' => $orderMarketplace->itemOrders()
                                ->where('status', true)
                                ->get()
                                ->map(function ($itemOrder) {
                                    $date = $itemOrder->sale_type == OrderSaleType::RENT
                                        ? $itemOrder->rentDetailsMarketplace->date_start->format('d/m/Y')
                                        : $itemOrder->created_at->format('d/m/Y');

                                    $duration = null;
                                    $start_date = null;
                                    $end_date = null;

                                    if ($itemOrder->sale_type == OrderSaleType::RENT->value) {
                                        $rentDetails = $itemOrder->rentDetailsMarketplace;
                                        $duration = $rentDetails->date_start->diffInDays($rentDetails->date_end) + 1;
                                        $start_date = $rentDetails->date_start->format('d/m/Y');
                                        $end_date = $rentDetails->date_end->format('d/m/Y');
                                    }

                                    return [
                                        'product_full_name' => $itemOrder->item->product->full_name,
                                        'size' => $itemOrder->item->variant->size->full_name,
                                        'color' => $itemOrder->item->variant->color?->name,
                                        'date' => $date,
                                        'sale_type' => $itemOrder->sale_type,
                                        'item_price' => $itemOrder->item_price,
                                        'duration' => $duration,
                                        'start_date' => $start_date,
                                        'end_date' => $end_date,
                                    ];
                                }),
                            'totals' => (object) [
                                'sale_amount' => number_format($orderMarketplace->itemOrders()
                                    ->where('sale_type', OrderSaleType::SALE)
                                    ->get()
                                    ->sum(function ($itemOrder) {
                                        return $itemOrder->item_price;
                                    }), 2, '.', ','),
                                'rent_amount' => number_format($orderMarketplace->itemOrders()
                                    ->where('sale_type', OrderSaleType::RENT)
                                    ->get()
                                    ->sum(function ($itemOrder) {
                                        $rentDetails = $itemOrder->rentDetailsMarketplace;
                                        $pricingScheme = $itemOrder->item->product->pricingScheme;

                                        $baseRentPrice = ($rentDetails->date_start->diffInDays($rentDetails->date_end) + 1 == 4)
                                            ? $pricingScheme->sku_4->price
                                            : $pricingScheme->sku_8->price;

                                        return $baseRentPrice;
                                    }), 2, '.', ','),
                                'employee' => $orderMarketplace->employee->full_name,
                                'insurance' => number_format($orderMarketplace->itemOrders()
                                    ->where('sale_type', OrderSaleType::RENT)
                                    ->get()
                                    ->sum(function ($itemOrder) {
                                        return $itemOrder->rentDetailsMarketplace->insurance_price ?? 0.00;
                                    }), 2, '.', ','),
                                'fitting_total' => number_format($orderMarketplace->itemOrders()
                                    ->where('status', true)
                                    ->get()
                                    ->sum(function ($itemOrder) {
                                        return $itemOrder->fitting_price ?? 0.00;
                                    }), 2, '.', ','),
                                'total' => number_format($orderMarketplace->amount_total, 2, '.', ','),
                                'advance_payment' => number_format($orderMarketplace->advance_payment, 2, '.', ','),
                                'pending' => number_format($orderMarketplace->pending, 2, '.', ','),
                            ],
                        ]
                    ]),
                default => throw new NotImplementedException('Document type not implemented'),
            };

            return Response::make($builder->toPdfContent(), 200, [
                'Content-Type' => 'application/pdf',
            ]);
        }, 'Could not find order', 'Error while retrieving order data');
    }

    private function getOrderStatuses(OrderMarketplace $orderMarketplace, array $data)
    {
        $orderStatuses = OrderStatuses::getAllNames();

        $disabledStatuses = [ // Always disabled
            OrderStatuses::TO_VALIDATE,
            OrderStatuses::CANCELLED,
            OrderStatuses::RETURNED,
        ];

        if ($data['total_payment'] < $orderMarketplace->amount_total) { // Disabled until paid
            $disabledStatuses[] = OrderStatuses::PAY;
            $disabledStatuses[] = OrderStatuses::CLOSED;
        }

        foreach ($disabledStatuses as $status) {
            unset($orderStatuses[$status->value]);
        }

        return $orderStatuses;
    }

    /**
     * Get the module we are actually on, based on route name
     *
     * @return string
     */
    protected function getCurrentModule()
    {
        return ModuleAliases::ORDER->value;
    }

    public function readAllRecords(Request $request)
    {
        try {
            $model = $this->getModelInstance();
            $perPage = (int) $request->get('per_page', 10);

            $currentFilters = $request->except(['page', 'per_page']);
            $previousFilters = session('previous_filters', []);
            if ($currentFilters != $previousFilters) {
                $request->merge(['page' => 1]);
            }
            session(['previous_filters' => $currentFilters]);

            $query = $model->query()
                ->when(
                    $this->searchField && $request->filled($this->searchField),
                    function ($q) use ($request) {
                        $searchTerm = $request->get($this->searchField);
                        $q->where(function ($subQuery) use ($searchTerm) {
                            $subQuery->where('code', 'LIKE', '%' . $searchTerm . '%');
                        });
                    }
                )
                ->when($request->filled('store_id'), fn($q) => $q->where('store_id', $request->get('store_id')))
                ->when($request->filled('created_at'), fn($q) => $q->whereDate('created_at', $request->get('created_at')))
                ->when($request->filled('date_start'), function ($q) use ($request) {
                    $q->whereHas('itemOrders.rentDetailsMarketplace', fn($q2) => $q2->whereDate('date_start', $request->get('date_start')));
                })
                ->when($request->filled('employee_id'), fn($q) => $q->where('employee_id', $request->get('employee_id')))
                // NUEVO: filtro por tipo de operación (venta/renta/ambas)
                ->when($request->filled('sale_type') && $request->get('sale_type') !== '', function ($q) use ($request) {
                    $type = $request->get('sale_type');
                    $sale = \App\Enums\OrderSaleType::SALE->value;
                    $rent = \App\Enums\OrderSaleType::RENT->value;

                    if ($type === 'venta') {
                        $q->whereHas('itemOrders', fn($qq) => $qq->where('sale_type', $sale))
                            ->whereDoesntHave('itemOrders', fn($qq) => $qq->where('sale_type', $rent));
                    } elseif ($type === 'renta') {
                        $q->whereHas('itemOrders', fn($qq) => $qq->where('sale_type', $rent))
                            ->whereDoesntHave('itemOrders', fn($qq) => $qq->where('sale_type', $sale));
                    } elseif ($type === 'renta_venta') {
                        $q->whereHas('itemOrders', fn($qq) => $qq->where('sale_type', $sale))
                            ->whereHas('itemOrders', fn($qq) => $qq->where('sale_type', $rent));
                    }
                });


            $this->beforeReadAll($query, $request);

            $data = $query->paginate($perPage);

            $postReadAllResponse = $this->afterReadAll($model);

            if ($this->searchField) {
                $postReadAllResponse['searchBy'] = $this->searchField;
            }

            $postReadAllResponse['stores'] = Store::whereNotIn(DB::raw('LOWER(name)'), ['almacén'])->get();
            $postReadAllResponse['employees'] = User::whereNotNull('remember_token')
                ->orderBy('name')
                ->get(['id', 'name', 'last_name']);

            return $this->makeResponse([
                'view' => $this->getIndexViewName(),
                'data' => [
                    "data" => $data,
                    'per_page' => $perPage,
                    ...$postReadAllResponse
                ],
                'message' => 'Records retrieved successfully',
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_OK,
            ]);
        } catch (\Exception $exception) {
            return $this->makeResponse([
                'message' => 'Error retrieving records',
                'success' => false,
                'status' => \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    public function getBarcodes($id)
    {
        try {
            $orderMarketplace = OrderMarketplace::with('itemOrders.item.product')->findOrFail($id);

            $items = $orderMarketplace->itemOrders->map(function ($itemOrder, $index) {
                return [
                    'number' => $index + 1,
                    'name' => $itemOrder->item && $itemOrder->item->product ? $itemOrder->item->product->name : 'Sin nombre',
                    'barcode' => $itemOrder->item ? $itemOrder->item->barcode : 'Sin código'
                ];
            })->filter(function ($item) {
                return $item['barcode'] !== 'Sin código';
            })->values();

            return response()->json([
                'success' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener información de artículos'
            ]);
        }
    }
}
