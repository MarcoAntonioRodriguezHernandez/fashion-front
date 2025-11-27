<?php

namespace App\Models\Marketplace;

use App\Enums\PaymentStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace\OrderMarketplace;
use App\Models\Base\PaymentType;

class PaymentOrderMarketplace extends Model
{
    use HasFactory;
    /**
     * The name of the table in the database for the template `PaymentOrderMarketplace`.
     *
     * @var string
     */
    protected $table = 'payment_order_marketplace';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_marketplace_id',
        'total', // Total amount of the payment
        'payment', // Real amount (after discount client credit)
        'payment_type_id',
        'status',
        'reason',
        'note_reason', // Additional note for the reason
    ];

    protected $casts = [
        'total' => 'float',
        'payment' => 'float',
    ];

    protected $appends = ['to_credit'];

    public function getToCreditAttribute()
    {
        return $this->total - $this->payment;
    }

    public function orderMarketplace()
    {
        return $this->belongsTo(OrderMarketplace::class, 'order_marketplace_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function getStatusNameAttribute()
    {
        return PaymentStatuses::getName($this->status);
    }
}
