<?php

namespace App\Models\Support;

use App\Models\Base\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'amount_processed',
        'finished_at',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
