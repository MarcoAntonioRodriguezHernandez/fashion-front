<?php

namespace App\Models\Examples;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExampleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    public function examples()
    {
        return $this->hasMany(Example::class, 'example_type_id');
    }
}
