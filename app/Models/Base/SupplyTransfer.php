<?php

namespace App\Models\Base;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyTransfer extends Model
{
    use HasFactory;
    protected $table = 'supply_transfers';

    protected $fillable = [
        'supply_id',   
        'recipient_id',  
        'reception_date', 
        'origin_id',
        'destination_id', 
    ];

    public function getIsDeliveredAttribute()
    {
        return $this->recipient_id != null && $this->reception_date != null;
    }

    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }

    public function recipient()
    {
        return $this->belongsTo(User::class);
    }

    public function origin()
    {
        return $this->belongsTo(Store::class, 'origin_id');
    }

    public function destination()
    {
        return $this->belongsTo(Store::class, 'destination_id');
    }

    public function suppliedItems()
    {
        return $this->hasMany(SuppliedItem::class);
    }
}
