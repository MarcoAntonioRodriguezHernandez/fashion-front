<?php

namespace Database\Factories\Base;

use App\Enums\CategoryStatuses;
use App\Models\Base\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'parent_category_id' => Category::inRandomOrder()->first()?->id,
            'status' => CategoryStatuses::ACTIVE,
        ];
    }
}
