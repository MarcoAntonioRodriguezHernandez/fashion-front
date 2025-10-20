<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'variant_id',
        'product_image_id',
    ];

    /**
     * Relationship with variant
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Relationship with product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function productImage()
    {
        return $this->belongsTo(ProductImage::class);
    }
}
