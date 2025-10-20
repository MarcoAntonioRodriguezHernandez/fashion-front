<?php

namespace App\Models\Base;

use App\Enums\{
    CouponTypes,
    OrderSaleType,
    StoreStatuses,
};
use App\Models\Base\CouponOrderMarketplace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'uses_amount',
        'category_id',
        'sale_type',
        'min_products',
        'discount',
        'coupon_type',
        'date_start',
        'date_end',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(OrderSaleType::class);
    }

    public function getFormattedDiscountAttribute()
    {
        return match ($this->coupon_type) {
            CouponTypes::PERCENTAGE->value => $this->discount . '%',
            CouponTypes::FIXED->value => '$'. $this-> discount,
            default => $this->discount
        };
    }

    protected function getTypeNameAttribute()
    {
        return StoreStatuses::getName($this->status);
    }
    public function couponOrderMarketplace()
    {
        return $this->hasMany(CouponOrderMarketplace::class);
    }
}
