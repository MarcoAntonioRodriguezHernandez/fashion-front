<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Color,
    Marketplace,
    Size,
    Sku,
    Variant,
};
use App\Models\Marketplace\OrderMarketplace;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\MarketplaceCode>
 */
class MarketplaceCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $codableName = fake()->randomElement([
            Variant::class,
            Sku::class,
            Color::class,
            Size::class,
        ]);

        return [
            'codable_type' => $codableName,
            'codable_id' => ($codableName)::all()->random()->id,
            'marketplace_id' => Marketplace::all()->random()->id,
            'code' => fake()->unique()->numerify('####'),
        ];
    }
}
