<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
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
        'hexadecimal',
        'texture_src',
        'parent_color_id',
        'status',
    ];

    public function scopeOnlyParents($query)
    {
        return $query->whereNull('parent_color_id');
    }

    public function scopeOnlyChildren($query)
    {
        return $query->whereNotNull('parent_color_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function productImage()
    {
        return $this->hasMany(ProductImage::class,'color_id');
    }

    public function children()
    {
        return $this->hasMany(Color::class, 'parent_color_id')->active();
    }

    public function parentColor()
    {
        return $this->belongsTo(Color::class, 'parent_color_id');
    }
}
