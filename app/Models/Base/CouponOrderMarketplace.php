<?php

namespace App\Models\Base;

use App\Models\Base\Coupon;
use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponOrderMarketplace extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'coupon_order_marketplace';
    protected $fillable = [
        'order_marketplace_id',
        'coupon_id',
        'user_id',
    ];

    public function orderMarketplace()
    {
        return $this->belongsTo(OrderMarketplace::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
