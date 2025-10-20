<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base\Tag;
use Illuminate\Support\Str;


class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        foreach ($this->name as $name) {
            Tag::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name)
            ]);
        }
    }

    private $name =[
        'Playa / Jardin ',
        'Night Out',
        'Cocktail / Inaguración',
        'Graduacion',
        'Boda de Dia',
        'Boda de Noche',
        'Dama de Honor',
        'Sorprende a tu ex',
        'Madrina de Bautizo',
        'Mama de la novia',
        'Mis XVs',
    ];
}
