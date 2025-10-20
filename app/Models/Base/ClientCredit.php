<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_detail_id',
        'amount',
        'expiration_date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (is_null($model->expiration_date)) {
                $model->expiration_date = now()->addMonths(config('common.client_credit_validity'));
            }
        });
    }
    
    public function clientDetail()
    {
        return $this->belongsTo(ClientDetail::class);
    }
}
