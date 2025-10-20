<?php

namespace Database\Factories\Base;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->ean13, 
            'name' => fake()->words(3, true), 
            'description' => fake()->sentence, 
            'duration' => fake()->numberBetween(1, 12), 
            'price' => fake()->numberBetween(100, 1000), 
        ];
    }
}
