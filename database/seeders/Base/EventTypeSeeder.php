<?php

namespace Database\Seeders\Base;

use App\Models\Base\EventType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->names as $name) {
            EventType::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }

    private $names = [
        'No Especificado',
        'Boda',
        'Graduación',
        'Bautizo',
        'Año Nuevo',
        'Cumpleaños',
        'Despedida',
        'XV Años',
    ];
}
