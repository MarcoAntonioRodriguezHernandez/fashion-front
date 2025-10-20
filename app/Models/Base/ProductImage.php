<?php

namespace App\Models\Base;

use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'color_id',
        'camera_perspective',
        'src_image',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($productImage) {
            $filesHandler = new class
            {
                use HandlerFilesTrait;
            };

            $filesHandler->deleteFileIfExists($productImage->src_image);

            $filesHandler->deleteDirectoryIfEmpty(pathinfo($productImage->src_image, PATHINFO_DIRNAME));
        });
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function supplyTransfer()
    {
        return $this->belongsTo(SupplyTransfer::class);
    }

    public function suppliedItem()
    {
        return $this->belongsTo(SuppliedItem::class);
    }

    public function getSrcImageAttribute($value)
    {
        $baseUrl = config('filesystems.disks.do.url');
        return $value ? rtrim($baseUrl, '/') . '/' . ltrim($value, '/') : null;
    }
}
