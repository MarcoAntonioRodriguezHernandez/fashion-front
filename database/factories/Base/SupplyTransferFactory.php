<?php

namespace Database\Factories\Base;

use App\Models\Base\Store;
use App\Models\Base\Supply;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplyTransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supply_id' => Supply::all()->random()->id,
            'recipient_id' => User::all()->random()->id,
            'reception_date' => $this->faker->dateTime(),
            'origin_id' => Store::all()->random()->id,
            'destination_id' => Store::all()->random()->id,
        ];
    }
}
