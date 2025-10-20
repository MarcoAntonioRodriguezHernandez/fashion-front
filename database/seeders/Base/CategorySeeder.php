<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Base\Category;
use Illuminate\Support\Str;
use App\Enums\CategoryStatuses;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->create([
            'name' => 'Genérico',
            'slug' => 'generico',
            'parent_category_id' => null,
            'status' => CategoryStatuses::NOT_VISIBLE,
        ]);

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding predefined data if the database should not be synced
            foreach ($this->names as $name) {
                Category::factory()->create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'status' => CategoryStatuses::ACTIVE,
                ]);
            }
        }
    }

    private $names = [
        'Vestidos',
        'Accesorios',
        'Bolsos'
    ];
}
