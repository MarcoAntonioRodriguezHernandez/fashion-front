<?php

namespace App\Models\Base;

use App\Models\User;
use App\Enums\SupplyStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Events\SupplyUpdatedEvent;
use Illuminate\Support\Facades\App;

class Supply extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'supplies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'code',
        'shipping_date',
        'status',
        'is_automatic',
    ];

    protected static function boot()
    {
        parent::boot();

        if (!App::runningInConsole()) {
            static::created(function ($supply) {
                event(new SupplyUpdatedEvent($supply, 'create'));
            });

            static::updated(function ($supply) {
                event(new SupplyUpdatedEvent($supply, 'update'));
            });
        }
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function getStatusNameAttribute()
    {
        return SupplyStatuses::getName($this->status);
    }

    public function getStatusColorAttribute()
    {
        return SupplyStatuses::getColor($this->status);
    }

    public function supplyTransfers()
    {
        return $this->hasMany(SupplyTransfer::class);
    }

    public function suppliedItems()
    {
        return $this->hasManyThrough(SuppliedItem::class, SupplyTransfer::class);
    }

    public static function generateCode(int $length = 8): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $code .= $characters[$index];
        }

        return $code;
    }
}
