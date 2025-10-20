<?php

namespace Database\Factories\Base;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Characteristics>
 */
class CharacteristicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => fake()->unique()->words(2, true),
            'slug' => fake()->unique()->slug(),
            'parent_characteristic_id' => null,
        ];
    }
}
