<?php

namespace App\Http\Controllers\Base\Item;

use App\Enums\ItemConditions;
use App\Exports\Excel\ConfigExport;
use App\Http\Controllers\Controller;
use App\Traits\Helpers\ResponseTrait;
use App\Traits\Base\CreatesExcel;
use App\Traits\Base\Item\FiltersItems;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Collection;
use App\Models\Base\{
    Item,
    Characteristic,
    Color,
    Designer,
    PricingScheme,
    Size,
    Store,
    Category,
};
use App\Http\Requests\Base\Item\{
    FilterRequest,
};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;


class ItemReportController extends Controller
{
    use CreatesExcel, ResponseTrait, FiltersItems;


    public function __construct()
    {
        $this->initializeCreatesExcel();
    }

    public function inventoryView(FilterRequest $request)
    {
        try {
            // Log::info($request);
            $itemsResult = Inertia::lazy(function () use ($request) {
                return $this->filterItemsBy((object) $request->all());
            });

            $itemInfo = Inertia::lazy(function () use ($request) {
                return $this->buildItemInfoData(Item::with('productVariant.product')->findOrFail($request->itemId));
            });

            $categories = Category::select('id', 'name')->orderBy('name')->get();
            $stores = Store::select('id', 'name')->orderBy('name')->get();
            $designers = Designer::select('id', 'name')->orderBy('name')->get();
            $sizes = Size::select('id', 'name')->orderBy('name')->get();
            $colors = Color::active()->onlyParents()->get()->mapWithKeys(fn ($c) => [$c->id => [
                'parent' => $c,
                'children' => $c->children,
            ]]);
            $pricingSchemes = PricingScheme::with('sku_4', 'sku_8')->get();

            $characteristics = Characteristic::onlyParents()->get()->map(fn ($c) => [
                'parent' => $c,
                'children' => $c->children,
            ]);
            $conditions = ItemConditions::getAllNames();

            return Inertia::render('Item/Inventory', compact('itemsResult', 'itemInfo', 'stores', 'designers', 'sizes', 'colors', 'characteristics', 'conditions', 'pricingSchemes', 'categories'));
        } catch (ModelNotFoundException $e) {
            return $this->makeResponse([
                'message' => 'Item not found',
                'success' => false,
                'status' => Response::HTTP_NOT_FOUND,
                'exception' => $e,
            ]);
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error while retrieving filter data',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e,
            ]);
        }
    }

    public function inventoryReportExcel(Request $request)
    {
        try {
            ini_set('max_execution_time', 300);
            $filename = 'inventory_report_' . date('Y-m-d_H-i-s');

            $data = $this->filterItemsBy($request, false);

            // Images have been removed from the report. Ensure no image fields remain.
            $data = $data->transform(function ($row) {
                if (is_array($row)) {
                    unset($row['product_variant_id'], $row['image_url'], $row['Imagen']);
                }
                return $row;
            });

            $path = $this->reportExportExcel($data, $filename);

            return response()->download($path, 'inventory_report_' . date('Y-m-d_H-i-s') . '.xlsx')->deleteFileAfterSend(true);
        } catch (Exception $e) {
            return $this->makeResponse([
                'message' => 'Error generating inventory report',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    protected function normalizeData(Collection $items, int|bool $pageSize = null)
    {
        return $items
            ->when($pageSize === false, fn ($c) => $c->map(fn (Item $i) => $this->buildItemDataExcel($i)))
            ->when($pageSize !== false, fn ($c) => $c->paginate($pageSize)->through(fn (Item $i) => $this->buildItemData($i)));
    }

    protected function makeConfig(): ConfigExport
    {
        $config = new ConfigExport;
        $config->rowHeight = 70;
        $config->widthImage = 100; // -1;
        $config->qualityImage = 40;

        return $config;
    }

    private function buildItemDataExcel(Item $item)
    {
        $productVariant = $item->productVariant;
        $product = $item->product;

        // Image column and image_url/product_variant_id removed from the export
        $data = [
            'ID' => $item->id,
            'Codigo de barras' => $item->barcode ?? 'Sin código',
            'Sucursal' => $item->store->name,
            'Condicion' => ItemConditions::getAllNames()[$item->condition] ?? 'Condición desconocida',
            'Diseñador' => $product->designer->name,
            'Nombre del producto' => $product->name,
            'Talla' => $item->variant->size->full_name,
            'Color' => $item->variant->color->name,
            'Categoria' => $product->category->name,
            'Renta' => optional($product->pricingScheme->sku_4)->price,
            'Venta' => $item->price_sale,
            'Venta Completa' => $item->price_sale,
        ];

        return $data;
    }

    protected function addImages(Collection $data): void
    {
        return;
    }

}
