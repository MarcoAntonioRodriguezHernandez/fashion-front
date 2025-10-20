<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryInvitation extends Model
{
    use HasFactory;

    const EXPIRATION_TIMES = [
        '1_hour' => 1,
        '1_day' => 24,
        '1_week' => 168,
    ];

    protected $fillable = [
        'token',
        'uses_left',
        'expiration',
        'invitation_type',
        'store_id',
        'email',
        'roles',
    ];

    protected $casts = [
        'expiration' => 'datetime'
    ];
}
