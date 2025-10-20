<?php

namespace App\Models\Base;

use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_type_id',
        'specification',
        'scheduled_date',
    ];

    public function ordersMarketplace()
    {
        return $this->hasMany(OrderMarketplace::class);
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }
}
