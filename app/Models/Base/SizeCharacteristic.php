<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeCharacteristic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'size_characteristic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'size_id',
        'characteristic_id',
    ];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function characteristic()
    {
        return $this->belongsTo(Characteristic::class);
    }
}
