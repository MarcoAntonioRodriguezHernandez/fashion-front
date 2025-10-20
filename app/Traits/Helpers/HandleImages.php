<?php

namespace App\Traits\Helpers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait HandleImages
{
    /**
     * Process, resize, and encode the image path.
     *
     * @param string $path
     * @param int|null $width
     * @param int|null $height
     * @param int|null $quality
     * @param string $disk
     * @return \Intervention\Image\Image
     */
    public function getAndResizeImage(string $path, int $width = null, $height = null, int $quality = null, string $disk = 'public')
    {
        $source = $this->getImageSource($path, $disk);
        $source ??= public_path('media/products/image404.png');

        $image = Image::make($source);

        if ($width !== -1) {
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $image = $image->encode('jpg', $quality);

        return $image;
    }


    /**
     * Create a base64 image from a content.
     * 
     * @param string $content
     * @return string
     */
    public function toBase64Format(string $content)
    {
        return 'data:image/jpeg;base64,' . base64_encode($content);
    }

    /**
     * Get the image source.
     * 
     * @param string $path
     * @param string $disk
     * 
     * @return string|null
     */
    public function getImageSource(string $path, string $disk = 'public')
    {
        $storage = Storage::disk($disk);
        $source = null;

        if (strpos($path, $storage->url('')) !== false) { // If image is in local storage
            $source = $storage->path(str_replace($storage->url(''), '', $path));

            $source = file_exists($source) ? $source : null;
        } else { // If image is an external URL
            try {
                $source = file_get_contents($path); // If the image doesn't exists, throw an error
            } catch (Exception $e) {
                $source = null;
            }
        }

        return $source;
    }
}
