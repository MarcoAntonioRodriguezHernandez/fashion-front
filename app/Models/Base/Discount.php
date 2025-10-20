<?php

namespace App\Models\Base;

use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_marketplace_id',
        'reason',
        'amount',
        'applies_to'
    ];

    public function orderMarketplace()
    {
        return $this->belongsTo(OrderMarketplace::class);
    }
}
