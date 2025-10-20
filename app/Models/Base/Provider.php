<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
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
        'contact',
        'email',
        'phone',
        'url',
        'country_id',
    ];

    public function designers()
    {
        return $this->belongsToMany(Designer::class, DesignerProvider::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
