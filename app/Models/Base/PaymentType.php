<?php

namespace App\Models\Base;

use App\Enums\PaymentTypeVisibilities;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
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
        'visibility',
    ];

    public function scopeVisible(Builder $query)
    {
        return $query->where('visibility', PaymentTypeVisibilities::VISIBLE->value);
    }
}