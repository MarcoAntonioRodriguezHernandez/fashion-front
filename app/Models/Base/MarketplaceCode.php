<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codable_type',
        'codable_id',
        'marketplace_id',
        'code',
    ];

    public function codable()
    {
        return $this->morphTo();
    }

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class);
    }
}
