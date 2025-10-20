<?php

namespace App\Services\Item;

use App\Models\Base\Item;
use App\Services\Support\ZipFiles;
use App\Traits\Base\{
    CodeImage,
    CreatesPdf,
};
use App\Traits\Helpers\HandleImages;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ZipArchive;

class ItemCardService
{
    use HandleImages, CodeImage, CreatesPdf {
        createPdfFile as genericPdfFile;
    }

    /**
     * @var string
     */
    private string|bool $logId;
    /**
     * @var array
     */
    private array $imageList;

    public function __construct(string|bool $logId = null)
    {
        $this->logId = $logId ?? $this->createLogId();
        $this->imageList = [];
    }

    /**
     * Create a new card file
     *
     * @param string $zipPath
     * @param array $values
     *
     * @return ZipArchive
     */
    public function createFullCardFile(string $zipPath, array $values)
    {
        $this->logCardsInfo('Starting generation of product cards', compact('values', 'zipPath'));

        $files = $this->createFilesFromValues($values);

        $this->logCardsInfo('PDF files created');

        $zipFile = $this->zipCardsFiles($files->toArray(), $zipPath);

        $this->logCardsInfo('Finishing generation of product card');

        return $zipFile;
    }

    /**
     * Create all the PDF files for the given values
     *
     * @param array $values
     *
     * @return \Illuminate\Support\Collection
     */
    public function createFilesFromValues(array $values)
    {
        $logoImage = $this->getLogoImage();

        $this->logCardsInfo('Logo image for product cards created');

        $data = $this->getItemsFromValues($values)
            ->map(fn($item) => $this->buildCardData($item))
            ->mapToGroups(fn($item) => [$item->store => $item]);

        $this->logCardsInfo('Data for product cards fetched');

        return $data->mapWithKeys(fn($d, $k) => [$k => $this->createPdfFile([
            'data' => $d,
            'logoImage' => $logoImage,
        ])]);
    }

    /**
     * Zip the PDF files
     *
     * @param array $files
     * @param string $zipPath
     *
     * @return ZipArchive
     */
    public function zipCardsFiles(array $files, string $zipPath)
    {
        return ZipFiles::make($files, 'pdf')->get($zipPath, fn($f) => $f->output());
    }

    /**
     * Create a new PDF file
     *
     * @param array $data
     *
     * @return \Mpdf\Mpdf
     */
    public function createPdfFile(array $data = [])
    {
        return $this->genericPdfFile($this->getViewName(), $data, $this->getDefaultOptions());
    }

    /**
     * Get the items from the given values
     *
     * @param array $values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getItemsFromValues(array $values)
    {
        return Item::query()
            ->with(['product.pricingScheme', 'product.designer:id,name', 'variant.size:id,name,number,hex_color', 'variant.color:id,hexadecimal,texture_src'])
            ->whereIn('id', $values)
            ->orWhereIn('barcode', $values)
            ->get();
    }

    /**
     * Build the data for a single item in the card
     *
     * @param Item $item
     *
     * @return object
     */
    protected function buildCardData(Item $item)
    {
        $product = $item->product;

        return (object) [
            'name' => $product->name,
            'store' => $item->store->slug,
            'designer' => $product->designer->name,
            'size' => [
                'name' => $item->variant->size->number ?? $item->variant->size->name,
                'color' => $item->variant->size->hex_color,
            ],
            'color' => [
                'hexadecimal' => $item->variant->color->hexadecimal,
                'texture' => $item->variant->color->texture_src,
            ],
            'product_image' => $this->getImageForItem($item),
            'barcode_image' => $item->barcode ? $this->createBarcode($item->barcode) : '',
            'qrcode_image' => $item->barcode ? $this->createQRCode($item->barcode) : '',
            'prices' => [
                'sale' => number_format($product->full_price, 2),
                'rent_4_days' => number_format($product->pricingScheme->sku_4->price, 2),
                'rent_8_days' => number_format($product->pricingScheme->sku_8->price, 2),
            ],
        ];
    }

    /**
     * Get the image for the item
     *
     * @param Item $item
     *
     * @return string
     */
    protected function getImageForItem(Item $item)
    {
        $productImage = $item->productVariant->productImage;

        if (!key_exists($productImage->id, $this->imageList)) {
            $imageContent = $this->getAndResizeImage($productImage->src_image, 800, null, 75)->getEncoded();
            $imageContent = $this->toBase64Format($imageContent);

            Log::channel('item_cards')->info('Esta es la ruta de la imagen: ' . $productImage->src_image);
            Log::channel('item_cards')->info('(' . $this->logId . ') Imagen del producto obtenida: ' . $productImage->id);
            $this->imageList[$productImage->id] = $imageContent;
        }

        return $this->imageList[$productImage->id];
    }

    /**
     * Register a new log in the item cards channel
     *
     * @param string $message
     * @param mixed $context
     *
     * @return void
     */
    public function logCardsInfo(string $message, $context = [])
    {
        if ($this->logId === false)
            return;

        Log::channel('item_cards')->info('(' . $this->logId . ') ' . $message, $context);
    }

    /**
     * Get the logo image for the PDF
     */
    protected function getLogoImage()
    {
        $logoImage = public_path('media/logos/logoPDF-CM.jpg');

        return $this->toBase64Format(file_get_contents($logoImage));
    }

    /**
     * Get the default options for the PDF
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'isRemoteEnabled' => true,
        ];
    }

    /**
     * Get the view name for the PDF
     *
     * @return string
     */
    protected function getViewName()
    {
        return 'base.item.card-item-pdf';
    }

    /**
     * Create a random log id
     *
     * @return string
     */
    public function createLogId()
    {
        return strtolower(Str::random(5));
    }
}
