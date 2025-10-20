<?php

namespace Database\Seeders\Base;

use App\Models\Base\Characteristic;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacteristicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->data as $characteristic) {
            $parentCharacteristic = Characteristic::create([
                'name' => $characteristic['name'],
                'slug' => Str::slug($characteristic['name']),
                'parent_characteristic_id' => null,
            ]);

            foreach ($characteristic['children'] as $charName) {
                Characteristic::create([
                    'name' => $charName,
                    'slug' => Str::slug($charName),
                    'parent_characteristic_id' => $parentCharacteristic->id,
                ]);
            }
        }
    }

    private  $data = [
        [
            "name" => "Lineas",
            "children" => [
                'Bridesmaids',
                'Linea Lentejuela',
                'Mother of the bride',
                'Cocktail',
                'Licra/ultrasexy',
                'Plus',
                'Etiqueta',
                'Maternity',
                'Prom',
                'garden/Playa',
                'Minimal Cool',
                'Wedding guest',
            ],
        ],
        [
            "name" => "Largos",
            "children" => [
                'Cortilargos',
                'Midi',
                'Corto',
                'mini midi',
                'Largo Jumpsuit',
                'Largo',
            ],
        ],
        [
            "name" => "Mangas",
            "children" => [
                'A un hombro',
                'Manga 3/4',
                'Tirantes',
                'Corta',
                'Halter',
                'Sin manga',
                'Larga',
                'Strapless',
            ],
        ],
        [
            "name" => "Telas",
            "children" => [
                'Crepe',
                'Jaqueard',
                'Metalizada',
                'Satín',
                'Encaje',
                'Tela Lentejuela',
                'Neopreno',
                'Tafeeta',
                'Estaampado brillante',
                'Licra',
                'Orgaza',
                'Terciopelo',
                'Gasa',
                'Mesh',
                'Poliester',
                'Tul',
            ],
        ],
        [
            "name" => "Aplicaciones",
            "children" => [
                'Bordado en 3D',
                'Cinturon',
                'Cadena',
                'Guantes',
                'Capa',
                'Piedreria',
                'Cauda',
                'Plumas',
            ],
        ],
        [
            "name" => "Cortes",
            "children" => [
                'Ampon',
                'Circular',
                'Lápiz/Recto',
                'Princesa',
                'Trompeta',
                'Asimetrico',
                'Cut-Out',
                'Linea A',
                'Romper',
                'Tubular',
                'Bustier',
                'Imperio',
                'Mini',
                'Sirena',
                'Camisero',
                'Corte Jumpsuit',
                'Peplum',
                'Scretch',
            ],
        ],
    ];
}
