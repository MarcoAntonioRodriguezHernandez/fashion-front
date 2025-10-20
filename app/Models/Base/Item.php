<?php

namespace App\Models\Base;

use App\Enums\{
    ItemConditions,
    ItemIntegrities,
    ItemStatuses,
};
use App\Models\Marketplace\{
    ItemOrderMarketplace,
    RentDetailMarketplace,
};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Base\Product;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_variant_id',
        'store_id',
        'serial_number',
        'barcode',
        'price_sale',
        'price_purchase',
        'status',
        'invoice_id',
        'condition',
        'integrity',
        'is_new',
        'details',
    ];

    protected $with = ['productVariant'];
    protected $appends = ['is_in_transit', 'target_store_name', 'target_recipient_name'];

    /** 
     * Booted method
     */
    public static function booted(): void
    {
        static::updating(function ($item) {
            if ($item->isDirty('condition')) {
                $price = ItemConditions::getPriceFunction($item->condition)($item->product, $item);
                $item->price_sale = $price;
            }
        });
    }

    public function getVariantCodeAttribute()
    {
        return $this->productVariant->variant->marketplaceCode;
    }

    /**
     * Relationship with product variant
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function variant()
    {
        return $this->hasOneThrough(Variant::class, ProductVariant::class, 'id', 'id', 'product_variant_id', 'variant_id');
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id', 'id', 'product_variant_id', 'product_id');
    }

    /**
     * Relationship with store  
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function itemOrderMarketplaces()
    {
        return $this->hasMany(ItemOrderMarketplace::class)->with('orderMarketplace');
    }

    public function rentDetailsMarketplace()
    {
        return $this->hasManyThrough(RentDetailMarketplace::class, ItemOrderMarketplace::class);
    }

    public function getLatestItemOrderMarketplaceAttribute()
    {
        return $this->itemOrderMarketplaces()->latest()->get()->first();
    }

    public function getLatestActiveSuppliedItemAttribute()
    {
        return $this->suppliedItems()->latest()->where('delivered', false)->first();
    }

    public function supplyTransfers()
    {
        return $this->belongsToMany(SupplyTransfer::class, 'supplied_items');
    }

    public function suppliedItems(): HasMany
    {
        return $this->hasMany(SuppliedItem::class, 'item_id', 'id');
    }

    public function getSuppliesAttribute()
    {
        return Supply::whereHas('supplyTransfers.suppliedItems', function ($query) {
            $query->where('item_id', $this->id);
        })->get();
    }

    public function getConditionNameAttribute()
    {
        return ItemConditions::getName($this->condition);
    }

    public function getConditionColorAttribute()
    {
        return ItemConditions::getColor($this->condition);
    }

    public function getStatusNameAttribute()
    {
        return ItemStatuses::getName($this->status);
    }

    public function getStatusColorAttribute()
    {
        return ItemStatuses::getColor($this->status);
    }

    public function getIntegrityNameAttribute()
    {
        return ItemIntegrities::getName($this->integrity);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function openSuppliedItem()
    {
        return $this->hasOne(\App\Models\Base\SuppliedItem::class, 'item_id')
            ->where('delivered', false)
            ->latestOfMany();
    }

    public function getIsInTransitAttribute(): bool
    {
        return $this->openSuppliedItem()->exists();
    }
    public function getCurrentOpenSupplyTransferAttribute(): ?\App\Models\Base\SupplyTransfer
    {
        return $this->openSuppliedItem?->supplyTransfer;
    }
    public function getTargetStoreNameAttribute(): ?string
    {
        return optional($this->currentOpenSupplyTransfer?->destination)->name;
    }

    public function getTargetRecipientNameAttribute(): ?string
    {
        return optional($this->currentOpenSupplyTransfer?->recipient)->full_name;
    }
}
