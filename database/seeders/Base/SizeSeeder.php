<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base\Size;
use Illuminate\Support\Str;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->names as $name) {
            Size::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }

    private $names = [
        'XS', 'S', 'M', 'L', 'XL', 'XXL'
    ];
}
