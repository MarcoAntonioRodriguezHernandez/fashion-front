<?php

namespace App\Models\Base;

use App\Enums\CategoryStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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
        'parent_category_id',
        'status',
    ];

    public static function getByStatus(CategoryStatuses ...$statuses)
    {
        $statuses = array_map(fn ($status) => $status->value, $statuses);

        return self::whereIn('status', $statuses)->get();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function characteristics()
    {
        return $this->belongsToMany(Characteristic::class);
    }

    public function marketplaceCode()
    {
        return $this->morphMany(MarketplaceCode::class, 'codable');
    }

    public function getStatusNameAttribute()
    {
        return CategoryStatuses::getName($this->status);
    }

    public function getStatusColorAttribute()
    {
        return CategoryStatuses::getColor($this->status);
    }
}
