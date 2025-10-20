<?php

namespace App\Models\Examples;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use HasFactory;

    protected $with = ['exampleType'];

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'creation',
        'value',
        'quantity',
        'status',
        'example_type_id',
    ];

    public function exampleType()
    {
        return $this->belongsTo(ExampleType::class, 'example_type_id');
    }
}
