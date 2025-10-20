<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Base\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentParentId = null;

        foreach ($this->names as $name => $hexadecimal) {
            $isParent = Str::contains($name, 'Tonos') || $name == 'Multicolor/Print';

            $color = Color::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'hexadecimal' => $hexadecimal,
                'parent_color_id' => $isParent ?  null : $currentParentId,
            ]);

            if ($isParent)
                $currentParentId = $color->id;
        }
    }

    private $names = [
        'Tonos Negros' => '#000000',
        'Negro' => '#000000',
        'Tonos Azules' => '#0000FF',
        'Azul Rey' => '#4169E1',
        'Azul Cielo' => '#87CEEB',
        'Azul Petroleo' => '#008080',
        'Tonos Rojos' => '#FF0000',
        'Rojo Carmesi' => '#DC143C',
        'Rojo Tinto' => '#8B0000',
        'Tonos Rosas' => '#FFC0CB',
        'Palo de Rosa' => '#D8A8A1',
        'Fucsia' => '#FF00FF',
        'Tonos Verdes' => '#008000',
        'Verde Esmeralda' => '#50C878',
        'Verde Sage' => '#9DC183',
        'Verde Hunter' => '#355E3B',
        'Tonos Morado' => '#800080',
        'Magenta' => '#FF00FF',
        'Multicolor/Print' => '#FF69B4',
    ];
}
