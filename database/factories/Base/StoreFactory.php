<?php

namespace Database\Factories\Base;

use App\Enums\StoreTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\StoreStatuses;
use App\Models\Base\Marketplace;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => fake()->bothify('##??##?#'),
            'marketplace_id' => Marketplace::all()->random()->id,
            'lat' => fake()->randomFloat(5, 100, 2000),
            'long' => fake()->randomFloat(5, 100, 2000),
            'cp' => fake()->numerify('#####'),
            'address' => fake()->address(),
            'municipality' => fake()->words(5, true),
            'store_type' => fake()->randomElement([StoreTypes::SHOP, StoreTypes::STORE]),
            'status' => StoreStatuses::ACTIVE,
        ];
    }
}
