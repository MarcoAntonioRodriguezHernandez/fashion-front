<?php

namespace App\Models\Base;

use App\Enums\ProductSaleTypes;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'title',
        'slug',
        'origin_code',
        'internal_code',
        'full_price',
        'description',
        'designer_id',
        'category_id',
        'pricing_scheme_id',
        'desired',
        'sale_type',
        'sku',
        'status',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'full_price' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', '=', '1'); 
        });

        static::updated(function ($product) {
            if ($product->isDirty('full_price')) { // If the product price was updated
                // Update price of all related items
                $product->items()->update([
                    'price_sale' => $product->full_price,
                ]);
            }
        });

        static::deleting(function ($product) {
            $filesHandler = new class
            {
                use HandlerFilesTrait;
            };

            $product->images->each(function ($productImage) use ($filesHandler) {
                $filesHandler->deleteFileIfExists($productImage->src_image);

                $filesHandler->deleteDirectoryIfEmpty(pathinfo($productImage->src_image, PATHINFO_DIRNAME));
            });
        });
    }
 
    /**
     * Get product's full name
     */
    public function getFullNameAttribute()
    {
        return $this->title . ', ' . $this->name;
    }

    /**
     * Get product's name slug
     */
    public function getNameSlugAttribute()
    {
        return Str::slug($this->name);
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function designer()
    {
        return $this->belongsTo(Designer::class, 'designer_id');
    }

    public function pricingScheme()
    {
        return $this->belongsTo(PricingScheme::class);
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, ProductVariant::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, ProductTag::class);
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class, ProductProvider::class);
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, CharacteristicProduct::class);
    }

    public function getSaleTypeNameAttribute()
    {
        return ProductSaleTypes::getAllNames()[$this->sale_type] ?? 'Desconocido';
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getFirstImageAttribute()
    {
        return $this->images->first();
    }

    public function frontImages()
    {
        return $this->images()->where('camera_perspective', 'front');
    }

    public function backImages()
    {
        return $this->images()->where('camera_perspective', 'back');
    }

    public function rightImages()
    {
        return $this->images()->where('camera_perspective', 'right');
    }

    public function leftImages()
    {
        return $this->images()->where('camera_perspective', 'left');
    }

    public function sku()
    {
        return $this->hasOne(Sku::class, 'sku', 'sku');
    }
}
