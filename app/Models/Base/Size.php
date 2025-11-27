<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Size extends Model
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
        'number',
        'hex_color',
        'status',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return Str::of($this->name)->when($this->number !== null, fn ($s) => $s->append(' (' . $this->number . ')'));
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class, SizeCharacteristic::class);
    }
}
