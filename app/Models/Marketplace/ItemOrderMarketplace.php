<?php

namespace App\Models\Marketplace;

use App\Enums\{
    ItemConditions,
    OrderSaleType,
};
use App\Models\Base\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ItemOrderMarketplace extends Model
{
    use HasFactory;

    /**
     * The name of the table in the database for the template `OrderMarketplace`.
     *
     * @var string
     */
    protected $table = 'item_order_marketplace';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'order_marketplace_id',
        'additional_notes',
        'fitting_price',
        'item_price',
        'fitting_notes',
        'sale_type',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sale_type' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->item->update([
                'is_new' => ItemConditions::PRE_LOVED,
            ]);
        });
    }

    public function getItemCurrentPriceAttribute()
    {
        $rentDetailMarketplace = $this->rentDetailsMarketplace;
        $duration = $rentDetailMarketplace?->date_start->diffInDays($rentDetailMarketplace->date_end) + 1;

        return match ($this->sale_type) {
            OrderSaleType::RENT->value => $this->item->product->pricingScheme->{'sku_' . $duration}->price,
            OrderSaleType::SALE->value => $this->item->price_sale,
        };
    }
    
    public function getTotalPriceAttribute()
    {
        return $this->item_price +
            $this->fitting_price +
            $this->rentDetailsMarketplace?->insurance_price ?? 0;
    }

    /*
    *Relationships with 'product_marketplace' table
    */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /*
    *Relationships with 'order_marketplace' table
    */
    public function orderMarketplace()
    {
        return $this->belongsTo(OrderMarketplace::class, 'order_marketplace_id');
    }

    /*
    *Relationships with 'rent_detail_marketplace' table
    */
    public function rentDetailsMarketplace()
    {
        return $this->hasOne(RentDetailMarketplace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getSaleNameAttribute()
    {
        return match ($this->sale_type) {
            OrderSaleType::RENT->value => 'Renta',
            OrderSaleType::SALE->value => 'Venta',
            default => 'Desconocido',
        };
    }
}
