<?php

namespace App\Models\Base;

use App\Enums\ItemIntegrities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SupplyStatuses;

class SuppliedItem extends Model
{
    use HasFactory;

    protected $table = 'supplied_items';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'supply_transfer_id',
        'item_id',
        'delivered',
        'status',
        'integrity',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];
    
    public static function boot()
    {
        parent::boot();

        static::updated(function ($suppliedItem) {
            if ($suppliedItem->delivered) {
                $suppliedItem->item->update($suppliedItem->integrity ? [
                    'integrity' => $suppliedItem->integrity,
                ] : []);
            }
        });
    }

    /**
     * Get the item that owns the supplied item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the name that indicates the status of the supplied item.
     */
    public function getStatusNameAttribute()
    {
        return SupplyStatuses::getName($this->status);
    }

    /**
     * Get the name that indicates the status of the supplied item.
     */
    public function getIntegrityNameAttribute()
    {
        return ItemIntegrities::getName($this->integrity);
    }

    /**
     * Get the color that indicates the status of the supplied item.
     */
    public function getStatusColorAttribute()
    {
        return SupplyStatuses::getColor($this->status);
    }

    /**
     * Get the supply transfer that owns the supplied item.
     */
    public function supplyTransfer()
    {
        return $this->belongsTo(SupplyTransfer::class);
    }
    public function getIsLockedAttribute(): bool
    {
        $d = $this->details ?? [];
        return (bool)($d['locked'] ?? false);
    }

    public function getRedirectedToSupplyIdAttribute(): ?int
    {
        return $this->details['redirected_to_supply_id'] ?? null;
    }

    public function getRedirectedToSupplyTransferIdAttribute(): ?int
    {
        return $this->details['redirected_to_supply_transfer_id'] ?? null;
    }

    public function getRedirectedToSuppliedItemIdAttribute(): ?int
    {
        return $this->details['redirected_to_supplied_item_id'] ?? null;
    }

    public function getPreviousSuppliedItemIdAttribute(): ?int
    {
        return $this->details['previous_supplied_item_id'] ?? null;
    }
}
