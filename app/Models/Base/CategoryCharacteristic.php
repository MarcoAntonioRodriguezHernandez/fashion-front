<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCharacteristic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_characteristic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'characteristic_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class);
    }
}
