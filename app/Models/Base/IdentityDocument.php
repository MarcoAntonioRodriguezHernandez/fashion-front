<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityDocument extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_marketplace_id',
        'front',
        'back',
        'signature',
    ];

    public function getFrontAttribute($value)
    {
        $baseUrl = config('filesystems.disks.do.url');
        return $value ? rtrim($baseUrl, '/') . '/' . ltrim($value, '/') : null;
    }

    public function getBackAttribute($value)
    {
        $baseUrl = config('filesystems.disks.do.url');
        return $value ? rtrim($baseUrl, '/') . '/' . ltrim($value, '/') : null;
    }
}
