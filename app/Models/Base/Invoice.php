<?php

namespace App\Models\Base;

use App\Enums\PaymentStatuses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'buyer',
        'payment_status',
        'issuance_date',
        'payment_type_id',
        'exchange_rate',
        'invoice_file',
    ];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function invoiceFile()
    {
        return $this->belongsTo(InvoiceFile::class, 'invoice_file')->withDefault([
            'file' => null,
        ]);
    }

    public function getStatusNameAttribute()
    {
        return PaymentStatuses::getName($this->payment_status);
    }

    public function getStatusColorAttribute()
    {
        return PaymentStatuses::getColor($this->payment_status);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'buyer');
    }
}
