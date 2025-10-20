<?php

namespace App\Models\Base;

use App\Enums\NotificationTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'notifications_allowed'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['notifications_allowed_types'];

    public function getNotificationsAllowedTypesAttribute()
    {
        return collect(explode('-', $this->notifications_allowed))
            ->map(fn($t) => NotificationTypes::tryFrom($t))
            ->filter();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
