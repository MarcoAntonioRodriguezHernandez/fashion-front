<?php

namespace App\Models\Marketplace;

use App\Models\Base\{
    ShippingPrice,
    UserAddress,
};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderShippingMarketplace extends Model
{
    use HasFactory;
    /**
     * The name of the table in the database for the template `OrderMarketplace`.
     *
     * @var string
     */
    protected $table = 'order_shipping_marketplace';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shipping_price_id',
        'order_marketplace_id',
        'user_address_id',
        'status',
    ];

    public function orderMarketplace()
    {
        return $this->belongsTo(OrderMarketplace::class, 'order_marketplace_id');
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function shippingPrice()
    {
        return $this->belongsTo(ShippingPrice::class);
    }
}
