<?php

namespace App\Traits\Base\Product;

use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
};
use App\Models\Base\{
    Characteristic,
    Color,
    Invoice,
    InvoiceFile,
    Item,
    Product,
    ProductImage,
    ProductVariant,
    Size,
    Store,
    Tag,
    Variant,
};
use App\Traits\Helpers\{
    HandlerFilesTrait,
    Support,
};
use Exception;
use Illuminate\Support\Str;

trait CreatesProduct
{

    use HandlerFilesTrait, Support;

    private $specialColor = [
        'azul marino',
        'verde esmeralda',
        'azul rey',
        'verde hunter',
        'palo de rosa',
        'rojo quemado',
        'color tinto',
        'palo rosa',
        'rojo cereza',
        'magenta obscuro',
        'rose gold',
        'blanco y negro',
        'azul claro',
        'rosa metálico',
        'rosa barbie',
        'azul cielo',
        'rosa brillante',
        'verde sage',
        'negro brillante',
        'azul petróleo',
        'color bronce',
        'mármol rosa',
        'negro y cristales',
        'plata con brillantes',
        'negro y plata',
        'color beige',
        'dorado metálico',
        'negro con cristales'
    ];

    private $color = [
        'mostaza',
        'negro',
        'rojo',
        'azul',
        'blue',
        'champagne',
        'rosa',
        'dorado',
        'tinto',
        'naranja',
        'verde',
        'menta',
        'celeste',
        'cobre',
        'blush',
        'blanco',
        'fucsia',
        'plata',
        'magenta',
        'multicolor',
        'terracota',
        'malva',
        'bronce',
        'lila',
        'rosegold',
        'amarillo',
        'café',
        'cyan',
        'turquesa',
        'morado',
        'oro'
    ];

    /**
     * Attach images, tags and providers to a product
     */
    protected function postCreateProduct(Product $product, array $images = [], array $tags = [], array $providers = [], array $characteristics = [])
    {
        foreach ($images as $imageData) {
            $srcImage = match (gettype($imageData['image'])) {
                'string' => $imageData['image'],
                default => $this->upload($imageData['image'], $product->name_slug),
            };

            ProductImage::create([
                'product_id' => $product->id,
                'color_id' => $imageData['color_id'],
                'camera_perspective' => $imageData['camera_perspective'],
                'src_image' => $srcImage,
            ]);
        }

        $tagIds = $this->getModelIds(collect($tags), Tag::class);

        $product->tags()->syncWithoutDetaching($tagIds);

        $product->providers()->syncWithoutDetaching($providers);

        $product->characteristics()->sync([]); // Remove old characteristics

        $charIds = [];

        foreach ($characteristics as $parentId => $charGroup) {
            $charIds = array_merge($charIds, $this->getModelIds(collect($charGroup), Characteristic::class, ['parent_characteristic_id' => $parentId])->toArray());
        }

        $product->characteristics()->syncWithoutDetaching($charIds);
    }

    protected function postFullCreateProduct(Product $product, array $invoice = [], array $inventory = [])
    {
        $invoice = $this->getOrCreateInvoice($invoice['id'], (object) $invoice['data']);

        $images = $product->images->mapToGroups(fn($image) => [$image->color_id => $image]);

        foreach ($inventory as $item) {
            $size = Size::find($item['size_id']);
            $color = Color::find($item['color_id']);

            $variant = Variant::firstOrCreate([
                'size_id' => $item['size_id'],
                'color_id' => $item['color_id']
            ], [
                'code' => Support::generateVariantCode($size->slug, $color->slug),
                'status' => 1,
            ]);

            $productVariant = ProductVariant::updateOrCreate([
                'product_id' => $product->id,
                'variant_id' => $variant->id,
            ], [
                'product_image_id' => $images->get($item['color_id'])?->first()->id ?? $product->first_image->id,
            ]);

            for ($i = 0; $i < $item['amount']; $i++) Item::create([
                'product_variant_id' => $productVariant->id,
                'store_id' => Store::where('slug', 'almacen')->first()->id,
                'serial_number' => Str::uuid(),
                'price_sale' => $item['price_sale'],
                'invoice_id' => $invoice->id,
                'price_purchase' => $item['price_sale'],
                'status' => $item['status'] ?? ItemStatuses::IMPORTATION,
                'condition' => ItemConditions::NEW,
                'integrity' => ItemIntegrities::HEALTHY->value,
                'details' => '',
            ]);
        }
    }

    protected function getOrCreateInvoice(mixed $invoiceId, object $invoiceData = null): Invoice
    {
        try {
            return Invoice::findOrFail($invoiceId);
        } catch (Exception $e) {
            $file = $invoiceData->invoice_file;
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $invoiceFile = InvoiceFile::create([
                'file' => $this->upload($file, 'invoices', 'public', $fileName),
            ]);

            return Invoice::create([
                'invoice_number' => $invoiceData->invoice_number,
                'payment_status' => $invoiceData->payment_status,
                'issuance_date' => $invoiceData->issuance_date,
                'payment_type_id' => $invoiceData->payment_type_id,
                'exchange_rate' => $invoiceData->exchange_rate,
                'buyer' => $invoiceData->buyer_id,
                'invoice_file' => $invoiceFile->id,
            ]);
        }
    }

    private function cleanTitle(string $title)
    {
        // Remove special colors first
        foreach ($this->specialColor as $special) {
            $title = str_ireplace($special, '', $title);
        }

        // Remove single-word colors
        foreach ($this->color as $c) {
            $title = str_ireplace($c, '', $title);
        }

        // Clean up extra spaces
        return trim(preg_replace('/\s+/', ' ', $title));
    }
}
