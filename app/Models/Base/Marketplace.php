<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace\ProductMarketplace;

class Marketplace extends Model
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
        'url',
    ];

    public function marketplaces()
    {
        return $this->hasMany(ProductMarketplace::class, 'marketplace_id');
    }
}
