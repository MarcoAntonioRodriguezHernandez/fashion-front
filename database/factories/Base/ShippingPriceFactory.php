<?php

namespace Database\Factories\Base;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\ShippingPrice>
 */
class ShippingPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' =>  fake()->words(2, true),
            'code' => strtoupper('ENVIO-' . fake()->word()),
            'price' => fake()->numberBetween(100, 1000),
        ];
    }
}
