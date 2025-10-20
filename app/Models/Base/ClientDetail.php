<?php

namespace App\Models\Base;

use App\Enums\Genders;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
    ];
    public function getGenderNameAttribute()
    {
        return Genders::getAllNames()[$this->gender] ?? 'Desconocido';
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function clientCredits()
    {
        return $this->hasMany(ClientCredit::class);
    }
}
