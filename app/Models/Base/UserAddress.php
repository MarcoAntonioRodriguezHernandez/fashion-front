<?php

namespace App\Models\Base;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'interior_number',
        'external_number',
        'street',
        'colony',
        'city',
        'state',
        'zip_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
