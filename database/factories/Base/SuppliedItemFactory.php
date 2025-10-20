<?php

namespace Database\Factories\Base;

use App\Models\Base\
{
    Item,
    SupplyTransfer,
};
use App\Enums\SupplyStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SuppliedItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supply_transfer_id' => SupplyTransfer::all()->random()->id,
            'item_id' => Item::all()->random()->id,
            'delivered' => fake()->boolean(80),
            'status' => fake()->randomElement(SupplyStatuses::cases()),
            'integrity' => fake()->randomElement(SupplyStatuses::cases()),
            'details' => fake()->sentence(10),
        ];
    }
}
