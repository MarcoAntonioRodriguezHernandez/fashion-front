<?php

namespace App\Http\Controllers\Base\Item;

use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
};
use App\Http\Controllers\Controller;
use App\Http\Requests\Base\Stock\AddStockRequest;
use App\Jobs\Marketplace\{
    RefreshInventoryItem,
    RefreshProduct,
    RefreshProductVariant,
};
use App\Models\Base\{
    Color,
    Invoice,
    InvoiceFile,
    Item,
    Product,
    ProductVariant,
    Size,
    Store,
    Variant,
};
use App\Models\User;
use App\Services\Marketplace\RefreshProductService;
use App\Traits\Helpers\{
    HandlerFilesTrait,
    ResponseTrait as HelpersResponseTrait,
    Support,
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class StockController extends Controller
{
    // COMMON CONFIG
    use HelpersResponseTrait, HandlerFilesTrait, Support;

    private RefreshProductService $refreshProductService;

    public function __construct()
    {
        $this->refreshProductService = new RefreshProductService();
    }

    public function addStockView()
    {
        $products = Product::all();
        $variants = Variant::all();
        $colors = Color::active()->onlyChildren()->get();
        $sizes = Size::all();
        $users = User::whereHas('employeeDetail')
            ->orWhereHas('clientDetail')
            ->get();
        return $this->makeResponse([
            'view' => 'base.item.stock.add',
            'data' => ['data' => compact('products', 'variants', 'users', 'colors', 'sizes')],
            'message' => 'Add stock view retrieved successfully',
            'status' => Response::HTTP_OK,
        ]);
    }

    protected function getOrCreateInvoice(Request $request, string $invoiceNumber): Invoice
    {
        $existingInvoice = Invoice::where('id', intval($invoiceNumber))->orWhere('invoice_number', $invoiceNumber)->first();

        if ($existingInvoice) {
            return $existingInvoice;
        }
        $folder = 'invoices';
        $file = $request->file('invoice.invoice_file');
        $fileName = $file->getClientOriginalName();
        $fileName = pathinfo($fileName, PATHINFO_FILENAME);
        $fileUrl = $this->upload($file, $folder, 'public', $fileName);

        $invoiceFile = InvoiceFile::create([
            'file' => $fileUrl,
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'payment_status' => $request->invoice['payment_status'],
            'issuance_date' => $request->invoice['issuance_date'],
            'payment_type_id' => $request->invoice['payment_type_id'],
            'exchange_rate' => $request->invoice['exchange_rate'],
            'buyer' => $request->invoice['buyer_id'],
            'invoice_file' => $invoiceFile['id'],
        ]);

        return $invoice;
    }

    public function addStock(AddStockRequest $request)
    {
        try {
            DB::beginTransaction();

            $createdItems = [];
            $invoice = $this->getOrCreateInvoice($request, $request->invoice['invoice_number'] ?? $request->invoice_id);
            $product = Product::find($request->product_id);

            foreach ($request->inventory as $inventory) {
                $color = Color::find($inventory['color_id']);
                $size = Size::find($inventory['size_id']);

                $variant = Variant::firstOrCreate([
                    'color_id' => $color->id,
                    'size_id' => $size->id,
                ], [
                    'code' => Support::generateVariantCode($size->slug, $color->slug),
                    'status' => 1,
                ]);

                $productVariant = $this->findProductVariant($product, $variant);

                for ($i = 0; $i < $inventory['amount']; $i++) {
                    $createdItems[] = Item::create([
                        'product_variant_id' => $productVariant->id,
                        'serial_number' => Str::uuid(),
                        'price_sale' => $inventory['price_sale'],
                        'store_id' => Store::where('slug', 'almacen')->first()->id,
                        'invoice_id' => $invoice->id,
                        'price_purchase' => $request->price_purchase,
                        'status' => $inventory['status'] ?? ItemStatuses::IMPORTATION,
                        'condition' => ItemConditions::NEW,
                        'integrity' => ItemIntegrities::HEALTHY,
                        'details' => '',
                    ]);
                }
            }

            DB::commit();

            $variantColorList = collect($request->inventory)->pluck('color_id')->unique();

            $this->refreshProductService->chainRefreshFromColors($product->slug, $variantColorList, [
                RefreshProduct::class,
                RefreshProductVariant::class,
                RefreshInventoryItem::class,
            ])->dispatch();

            return $this->makeResponse([
                'data' => ['data' => $createdItems],
                'message' => 'Records created successfully',
                'status' => Response::HTTP_CREATED,
                'redirect' => 'base.item.index',
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            return $this->makeResponse([
                'message' => 'Failed to create records',
                'success' => false,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exception' => $exception,
            ]);
        }
    }

    private function findProductVariant(Product $product, Variant $variant)
    {
        return ProductVariant::firstOrCreate([
            'product_id' => $product->id,
            'variant_id' => $variant->id,
        ], [
            'title' => $product->title,
            'product_image_id' => $product->images()->where('color_id', Variant::find($variant->id)->color_id)->first()?->id ?? $product->images()->first()?->id,
        ]);
    }

    public function getProductColors($productId)
    {
        try {
            $productImages = Product::findOrFail($productId)->images;

            $imageColorIds = $productImages->pluck('color_id')->unique()->toArray();

            // Obtener solo colores hijos
            $childColors = Color::whereNotNull('parent_color_id')->get();

            $colors = $childColors->map(fn($color) => [
                'id' => $color->id,
                'name' => $color->name,
                'slug' => $color->slug,
                'parent_color_id' => $color->parent_color_id,
                'has_image' => in_array($color->id, $imageColorIds),
            ]);

            return response()->json(['success' => true, 'colors' => $colors]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al obtener colores.']);
        }
    }
}
