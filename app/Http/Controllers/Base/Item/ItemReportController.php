<?php

namespace App\Http\Controllers\Base\Item;

use App\Enums\ItemConditions;
use App\Exports\Excel\ConfigExport;
use App\Http\Controllers\Controller;
use App\Traits\Helpers\ResponseTrait;
use App\Traits\Base\CreatesExcel;
use App\Traits\Base\Item\FiltersItems;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\Utils;
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

    private array $drawingObjects = [];

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

            $this->addImages($data);

            $data = $data->transform(function ($row) {
                unset($row['product_variant_id'], $row['image_url']);
                return $row;
            });

            $path = $this->reportExportExcel($data, $filename);

            $this->config->getStylesForImageCell($path);

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

        $data = [
            'Imagen' => '',
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
            'product_variant_id' => $productVariant->id,
            'image_url' => $productVariant->productImage->src_image
        ];

        return $data;
    }

    protected function addImages(Collection $data): void
    {
        // ini_set('memory_limit', '500M');
        $client = new Client();
        $promises = [];
        $variantIdToRows = [];

        $rowIndex = 2;
        foreach ($data as $row) {
            $variantId = $row['product_variant_id'];
            $variantIdToRows[$variantId][] = $rowIndex++;
        }

        $drawingCache = [];

        foreach ($variantIdToRows as $variantId => $_) {
            $url = $data->firstWhere('product_variant_id', $variantId)['image_url'] ?? null;
            if (!$url) continue;

            $promises[$variantId] = $client
                ->getAsync($url, ['verify' => false])
                ->then(function ($response) use (&$drawingCache, $variantId) {
                    $imageBytes = $response->getBody()->getContents();
                    $imageHash = md5($imageBytes);

                    if (isset($drawingCache[$imageHash])) return $drawingCache[$imageHash];

                    $tempFile = tempnam(sys_get_temp_dir(), 'img_');
                    file_put_contents($tempFile, $imageBytes);
                    $drawing = $this->createDrawingObject($tempFile, $this->config);
                    unlink($tempFile);
                    $drawingCache[$imageHash] = $drawing;

                    return $drawingCache[$imageHash];
                });
        }

        unset($drawingCache);
        $results = Promise\Utils::settle($promises)->wait();
        // Log::info('Results count: ' . count($results));

        // Insertar drawingObjects secuencialmente
        foreach ($variantIdToRows as $variantId => $rows) {
            $result = $results[$variantId];
            if ($result['state'] !== 'fulfilled') continue;
            
            $drawing = $result['value'];

            foreach ($rows as $row) {
                $clone = clone $drawing;
                $this->addDrawingObject($clone, 'A', $row);
            }
        
        }
        // Log::info('Memoria pico usada: ' . round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB');
    }

}
