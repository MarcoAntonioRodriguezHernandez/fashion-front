<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'duration',
        'price',
    ];

    public function priceSchemes()
    {
        return $this->hasMany(PricingScheme::class, 'sku_' . $this->duration . '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }

}
