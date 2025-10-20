<?php

namespace App\Models\Base;

use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'text',
        'link',
        'date',
        'order_marketplace_id',
        'user_id',
    ];

    public function orderMarketplace()
    {
        return $this->belongsTo(OrderMarketplace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderMarketplaceId()
    {
        return $this->belongsTo(OrderMarketplace::class,'order_marketplace_id');
    }
}
