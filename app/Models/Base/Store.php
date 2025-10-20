<?php

namespace App\Models\Base;

use App\Enums\{
        StoreTypes,
        StoreStatuses
};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace\ProductMarketplace;
class Store extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'code',
        'marketplace_id',
        'address',
        'lat',
        'long',
        'cp',
        'municipality',
        'store_type',
        'status',
    ];

    public static function getByStatus(StoreStatuses ...$statuses)
    {
        $statuses = array_map(fn ($status) => $status->value, $statuses);

        return self::whereIn('status', $statuses)->get();
    }

    public function examples()
    {
        return $this->hasMany(ProductMarketplace::class, 'store_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getStoreNameAttribute()
    {
        return match ($this->store_type) {
            StoreTypes::SHOP->value => 'Tienda',
            StoreTypes::STORE->value => 'Almacén',
            default => 'Desconocido',
        };
    }

    public function getStatusNameAttribute()
    {
        return StoreStatuses::getName($this->status);
    }

    public function getStatusColorAttribute()
    {
        return StoreStatuses::getColor($this->status);
    }

    public function marketplace()
{
    return $this->belongsTo(Marketplace::class, 'marketplace_id');
}
}
