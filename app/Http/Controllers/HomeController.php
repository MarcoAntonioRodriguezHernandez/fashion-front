<?php

namespace App\Http\Controllers;

use App\Models\Base\{
    Category,
    Product,
    Store,
    Item,
};
use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Enums\ItemStatuses;
use Carbon\Carbon;

class HomeController extends Controller
{
    private array $validKeys = ['aside-active'];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard(Request $request)
    {
        if (!$request->ajax() && ($request->has('page') || $request->has('store_id'))) {
            return redirect()->route('dashboard');
        }
        $stores = Store::all();
        $coords = Store::select('name', 'lat', 'long')->get()->toArray();

        $excludedStatuses = [
            ItemStatuses::ARCHIVED->value,
            ItemStatuses::LOST->value,
            ItemStatuses::SOLD->value,
            ItemStatuses::RENT->value,
            ItemStatuses::TRANSFER->value,
            ItemStatuses::TRAYECT->value,
            ItemStatuses::IMPORTATION->value,
        ];

        $warehouseId       = Store::where('name', 'Almacén')->value('id');
        $selectedStoreId   = request('store_id') ?: $warehouseId;
        $selectedStoreName = Store::where('id', $selectedStoreId)->value('name') ?: 'Almacén';
        $search = trim((string) $request->get('search', ''));

        $almacenProducts = Product::withCount([
            'items as almacen_count' => function ($q) use ($selectedStoreId, $excludedStatuses) {
                $q->whereHas('productVariant', fn($qq) => $qq->whereHas('variant'))
                    ->where('store_id', $selectedStoreId)
                    ->whereNotIn('status', $excludedStatuses);
            }
        ])
            ->whereHas('items', function ($q) use ($selectedStoreId, $excludedStatuses) {
                $q->where('store_id', $selectedStoreId)
                    ->whereNotIn('status', $excludedStatuses);
            })
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('products.name', 'like', "%{$search}%");
                });
            })
            ->with([
                'productVariants.variant.color',
                'productVariants.variant.size',
                'productVariants.items' => function ($q) use ($selectedStoreId, $excludedStatuses) {
                    $q->where('store_id', $selectedStoreId)
                        ->whereNotIn('status', $excludedStatuses);
                }
            ])
            ->orderByDesc('almacen_count')
            ->orderBy('products.id')
            ->paginate(15)
            ->appends([
                'store_id' => $selectedStoreId,
                'search'   => $search,
            ]);

        $totalStock = Item::where('store_id', $selectedStoreId)
            ->whereNotIn('status', $excludedStatuses)
            ->whereHas('productVariant', fn($q) => $q->whereHas('variant'))
            ->count();




        $products = Product::all();
        $orders = OrderMarketplace::limit(10)->get();
        $categories = Category::all();

        $sale = Product::withoutGlobalScope('active')
            ->select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('items', 'product_variants.id', '=', 'items.product_variant_id')
            ->join('item_order_marketplace', 'items.id', '=', 'item_order_marketplace.item_id')
            ->join('order_marketplace', 'item_order_marketplace.order_marketplace_id', '=', 'order_marketplace.id')
            ->with('images')
            ->where('products.status', 1)
            ->where('item_order_marketplace.sale_type', 1)
            ->selectRaw('SUM(order_marketplace.number_products) as total_sales')
            ->groupBy('products.id')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();
        Carbon::setWeekStartsAt(Carbon::SUNDAY);
        Carbon::setWeekEndsAt(Carbon::SATURDAY);
        $hoy = Carbon::today();
        $ayer = Carbon::yesterday();

        $ventasHoy = OrderMarketplace::whereDate('created_at', $hoy)->sum('amount');


        $ventasComparar = 0;
        $fechaComparar = Carbon::yesterday();
        $limiteBusqueda = Carbon::now()->subDays(30);

        while ($fechaComparar->greaterThan($limiteBusqueda)) {
            $ventasComparar = OrderMarketplace::whereDate('created_at', $fechaComparar)->sum('amount');

            if ($ventasComparar > 0) {
                break;
            }

            $fechaComparar->subDay();
        }

        if ($ventasComparar > 0) {
            $porcentajeCambio = round((($ventasHoy - $ventasComparar) / $ventasComparar) * 100, 2);
        } else {
            $porcentajeCambio = null;
        }


        $inicio = Carbon::now()->startOfWeek();
        $fin = $inicio->copy()->addDays(6);



        $semanaCompleta = [];
        $labels = [];
        $data = [];

        for ($date = $inicio->copy(); $date->lte($fin); $date->addDay()) {
            $clave = $date->toDateString();
            $semanaCompleta[$clave] = 0;
            $labels[] = $date->format('d M');
        }


        $ventasPorDia = OrderMarketplace::selectRaw('DATE(created_at) as fecha, SUM(amount) as total')
            ->whereBetween('created_at', [$inicio, $fin])
            ->groupBy('fecha')
            ->get();


        foreach ($ventasPorDia as $venta) {
            $fecha = $venta->fecha;
            if (isset($semanaCompleta[$fecha])) {
                $semanaCompleta[$fecha] = $venta->total;
            }
        }


        foreach ($semanaCompleta as $monto) {
            $data[] = $monto;
        }


        $totalDias = $ventasPorDia->count();
        $promedioDiario = $totalDias > 0
            ? round($ventasPorDia->sum('total') / $totalDias, 2)
            : 0;

        $textoSemana = 'Semana del ' . $inicio->format('d \d\e M') . ' al ' . $fin->format('d \d\e M');
        $rented = Product::withoutGlobalScope('active')
            ->select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('items', 'product_variants.id', '=', 'items.product_variant_id')
            ->join('item_order_marketplace', 'items.id', '=', 'item_order_marketplace.item_id')
            ->join('order_marketplace', 'item_order_marketplace.order_marketplace_id', '=', 'order_marketplace.id')
            ->with('images')
            ->where('products.status', 1)
            ->where('item_order_marketplace.sale_type', 2)
            ->selectRaw('SUM(order_marketplace.number_products) as total_sales')
            ->groupBy('products.id')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();


        return view('home.dashboard', compact(
            'stores',
            'products',
            'almacenProducts',
            'coords',
            'orders',
            'categories',
            'sale',
            'rented',
            'ventasHoy',
            'porcentajeCambio',
            'promedioDiario',
            'ventasPorDia',
            'labels',
            'data',
            'textoSemana',
            'selectedStoreId',
            'selectedStoreName',
            'totalStock'
        ));
    }

    /**
     * Update session value
     */
    public function sessionPut(Request $request)
    {
        if (!in_array($request->key, $this->validKeys)) // If key is not valid
            abort(404);

        if (isset($request->key) && isset($request->value)) // If both required are set
            Session::put($request->key, $request->value);
    }

    public function stockTable(Request $request)
    {
        $excludedStatuses = [
            ItemStatuses::ARCHIVED->value,
            ItemStatuses::LOST->value,
            ItemStatuses::SOLD->value,
            ItemStatuses::RENT->value,
            ItemStatuses::TRANSFER->value,
            ItemStatuses::TRAYECT->value,
            ItemStatuses::IMPORTATION->value,
        ];

        $warehouseId = Store::where('name', 'Almacén')->value('id');
        $selectedStoreId = request('store_id') ?: $warehouseId;
        $selectedStoreName = Store::where('id', $selectedStoreId)->value('name') ?: 'Almacén';

        $almacenProducts = Product::withCount([
            'items as almacen_count' => function ($query) use ($selectedStoreId, $excludedStatuses) {
                $query->whereHas('productVariant', fn($q) => $q->whereHas('variant'))
                    ->where('store_id', $selectedStoreId)
                    ->whereNotIn('status', $excludedStatuses);
            }
        ])
            ->whereHas('items', function ($q) use ($selectedStoreId, $excludedStatuses) {
                $q->where('store_id', $selectedStoreId)
                    ->whereNotIn('status', $excludedStatuses);
            })
            ->with([
                'productVariants.variant.color',
                'productVariants.variant.size',
                'productVariants.items' => function ($q) use ($selectedStoreId, $excludedStatuses) {
                    $q->where('store_id', $selectedStoreId)
                        ->whereNotIn('status', $excludedStatuses);
                }
            ])
            ->orderByDesc('almacen_count')
            ->paginate(15);

        $totalStock = Item::where('store_id', $selectedStoreId)
            ->whereNotIn('status', $excludedStatuses)
            ->whereHas('productVariant', fn($q) => $q->whereHas('variant'))
            ->count();

        return view('home.dashboard', compact(
            'stores',
            'products',
            'almacenProducts',
            'coords',
            'orders',
            'categories',
            'sale',
            'rented',
            'ventasHoy',
            'porcentajeCambio',
            'promedioDiario',
            'ventasPorDia',
            'labels',
            'data',
            'textoSemana',
            'selectedStoreId',
            'selectedStoreName',
            'totalStock'
        ));


        if ($request->ajax()) {
            return view('home.partials.stock_table', compact('almacenProducts'))->render();
        }

        return redirect()->route('dashboard');
    }

    public function productVariantsPartial(Product $product)
    {
        $excludedStatuses = [
            ItemStatuses::ARCHIVED->value,
            ItemStatuses::LOST->value,
            ItemStatuses::SOLD->value,
            ItemStatuses::RENT->value,
            ItemStatuses::TRANSFER->value,
            ItemStatuses::TRAYECT->value,
            ItemStatuses::IMPORTATION->value,
        ];

        $warehouseId = Store::where('name', 'Almacén')->value('id');
        $selectedStoreId = request('store_id') ?: $warehouseId;
        $variants = $product->productVariants()
            ->with([
                'variant.color',
                'variant.size',
                'items' => function ($q) use ($selectedStoreId, $excludedStatuses) {
                    $q->where('store_id', $selectedStoreId)
                        ->whereNotIn('status', $excludedStatuses);
                }
            ])
            ->get()
            ->groupBy(fn($pv) => $pv->variant->color->name . '-' . $pv->variant->size->name);

        return view('home.variant_info', compact('variants'));
    }
}
