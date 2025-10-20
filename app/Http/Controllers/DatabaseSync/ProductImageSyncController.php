<?php

namespace App\Http\Controllers\DatabaseSync;

use App\Http\Controllers\Controller;
use App\Models\Base\ProductImage;
use App\Traits\Helpers\{
    HandlerFilesTrait,
    ResponseTrait,
};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductImageSyncController extends Controller
{
    use HandlerFilesTrait, ResponseTrait;

    /**
     * @var array The error list ocurred during syncing
     */
    protected array $errors;

    public function __construct()
    {
        $this->errors = [];

        ini_set('max_execution_time', 1200);
    }

    public function syncLocalData(Request $request)
    {
        $images = $this->getImagesData($request->page, $request->size);

        foreach ($images as $image) {
            try {
                $imageSrc = $this->downloadImage($image->src_image, $image->product->name_slug);

                $image->update([
                    'src_image' => $imageSrc,
                ]);
            } catch (Exception $e) {
                $this->errors[] = $image->id . ' - ' . $image->product->name . ': ' . $e->getMessage();

                Log::channel('fatal')->error('Could not download image file  | ' . __METHOD__, [
                    'Status' => 'Error',
                    'Data' => $e->getMessage(),
                ]);
            }
        }

        return $this->makeResponse([
            'message' => 'Local records synced successfully, ' . $images->count() . ' fetched records, ' . count($this->errors) . ' errors',
            'status' => Response::HTTP_OK,
            'forceJson' => true,
            'data' => [
                'data' => $this->errors,
            ],
        ]);
    }

    public function syncLocalPaths(Request $request)
    {
        $images = $this->getImagesData($request->page, $request->size ?? 10000);

        $storage = $this->validateDisk('do');

        foreach ($images as $image) {
            $localPath = 'images/' . $image->product->name_slug . '/' . pathinfo($image->src_image, PATHINFO_FILENAME) . '.webp';

            if ($storage->exists($localPath)) {
                $image->update([
                    'src_image' => $storage->url($localPath),
                ]);
            } else {
                $this->errors[] = $image->id  . ' - ' . $image->product->name . ': Image not found in local storage (' . $localPath . ')';
            }
        }

        return $this->makeResponse([
            'message' => 'Local records synced successfully, ' . $images->count() . ' fetched records, ' . count($this->errors) . ' errors',
            'status' => Response::HTTP_OK,
            'forceJson' => true,
            'data' => [
                'data' => $this->errors,
            ],
        ]);
    }

    private function getImagesData(int $page = null, int $size = null)
    {
        $size ??= 500;
        $storage = $this->validateDisk('do');

        return ProductImage::with('product:id,name')
            ->select('id', 'src_image', 'product_id')
            ->where('src_image', 'NOT LIKE', $storage->url('') . '%')
            ->orderBy('product_id')
            ->get()
            ->when($page, fn (Collection $c) => $c->skip($size * ($page - 1))->take($size))
            ->values();
    }

    public function downloadImage(string $imageSrc, $folder = 'unknown')
    {
        return $this->upload(
            $imageSrc,
            'products/' . $folder,
            filename: pathinfo($imageSrc, PATHINFO_FILENAME),
            allowRename: false,
        );
    }
}
