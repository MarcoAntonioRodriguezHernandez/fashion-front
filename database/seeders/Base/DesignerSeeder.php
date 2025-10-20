<?php

namespace Database\Seeders\Base;

use App\Models\Base\Designer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DesignerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->names as $name) {
            $category = Designer::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }

    private $names = [
        'BADGLEY MISCHKA',
        'MONIQUE LHUILLIER',
        'MIKA Y KELLER',
        'TADASHI',
        'TIERRA ROSSA',
        'ABEJA REYNA',
        'TERANI COUTURE',
        'ULLA BUSQUETS',
        'TIFFANY ROSE',
        'DRESS THE POPULATION',
        'ELLE ZEITOUNE',
        'CONSPIRACION MODA',
        'WATI',
        'BARIANO',
        'JESSICA ANGEL',
        'LIKELY',
        'WE ARE THE ICONS',
        'BEC AND BRIDGE',
        'YUMI KIM',
        'ANDREA AND LEO',
        'MIRANDA TESS',
        'SONYA',
        'ELLE SAAB'
    ];
}
