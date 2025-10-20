<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    use HasFactory;

    protected $table = 'characteristics';

    protected $fillable = [
        'name',
        'slug',
        'parent_characteristic_id',
    ];

    public static function scopeOnlyParents($query)
    {
        return $query->whereNull('parent_characteristic_id');
    }

    public static function scopeOnlyChildren($query)
    {
        return $query->whereNotNull('parent_characteristic_id');
    }

    public function children()
    {
        return $this->hasMany(Characteristic::class, 'parent_characteristic_id');
    }

    public function parentCharacteristic()
    {
        return $this->belongsTo(Characteristic::class, 'parent_characteristic_id');
    }

}
