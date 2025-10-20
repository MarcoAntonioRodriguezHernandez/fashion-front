<?php

namespace App\Models\Marketplace;

use App\Enums\{
    OrderStatuses,
    PaymentStatuses,
};
use App\Models\Base\{
    CouponOrderMarketPlace,
    Discount,
    Event,
    IdentityDocument,
    Item,
    Marketplace,
    Notification,
    Store,
};
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Marketplace\PaymentOrderMarketplace;

class OrderMarketplace extends Model
{
    use HasFactory;

    /**
     * The name of the table in the database for the template `OrderMarketplace`.
     *
     * @var string
     */
    protected $table = 'order_marketplace';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'client_id',
        'code',
        'marketplace_id',
        'amount',
        'discount',
        'surcharge',
        'store_id',
        'number_products',
        'status',
        'date_canceled',
        'found_by',
        'event_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_active', 'is_enabled'];

    public function getOrderCodeAttribute()
    {
        return $this->attributes['code'];
    }

    public function getAmountTotalAttribute()
    {
        return $this->amount + $this->surcharge - $this->discount;
    }

    public function getAdvancePaymentAttribute()
    {
        return $this->paymentOrderMarketplace()->where('status', PaymentStatuses::APPROVED->value)->sum('payment');
    }

    public function getPendingAttribute()
    {
        return $this->amount_total - $this->advance_payment;
    }

    public function getIsActiveAttribute()
    {
        return in_array(
            $this->status,
            [
                OrderStatuses::TO_VALIDATE->value,
                OrderStatuses::PAY->value,
                OrderStatuses::WAITING_FOR_COLLECTION->value,
                OrderStatuses::RECEIVED_IN_STORE->value,
                OrderStatuses::DELIVERED_TO_CUSTOMER->value,
                OrderStatuses::DELIVERED_BY_CUSTOMER->value,
            ]
        ) &&
            $this->created_at->diffInHours(now()) < 24;
    }

    public function getIsEnabledAttribute()
    {
        return !in_array(
            $this->status,
            [
                OrderStatuses::CANCELLED->value,
                OrderStatuses::RETURNED->value,
            ]
        );
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function paymentOrderMarketplace()
    {
        return $this->hasMany(PaymentOrderMarketplace::class, 'order_marketplace_id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function orderShippingMarketplace()
    {
        return $this->hasOne(OrderShippingMarketplace::class);
    }

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class, 'marketplace_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function itemOrders()
    {
        return $this->hasMany(ItemOrderMarketplace::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, ItemOrderMarketplace::class)->withPivot(['sale_type']);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function couponOrderMarketplace()
    {
        return $this->hasMany(CouponOrderMarketplace::class);
    }

    public function identityDocument()
    {
        return $this->hasOne(IdentityDocument::class);
    }

    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
    public function client()
    {
        return $this->belongsTo(User::class);
    }
    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
