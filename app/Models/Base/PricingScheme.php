<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku_4_id',
        'sku_8_id',
        'msrp',
        'category_id',
        'increase',
    ];

    public function sku_4()
    {
        return $this->belongsTo(Sku::class, 'sku_4_id');
    }

    public function sku_8()
    {
        return $this->belongsTo(Sku::class, 'sku_8_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
