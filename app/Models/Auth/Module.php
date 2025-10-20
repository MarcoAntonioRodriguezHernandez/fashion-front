<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
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
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, Permission::class)->orderBy('role_id');
    }

    public function getNameAttribute()
    {
        return __($this->attributes['name']);
    }
}
