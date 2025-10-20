<?php

namespace Database\Factories\Base;

use App\Models\Base\Store;
use App\Models\User;
use App\Enums\SupplyStatuses;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::all()->random()->id,
            'code' => fake()->unique()->bothify('##??##?#'),
            'shipping_date' => $this->faker->dateTime(),
            'status' => fake()->randomElement(SupplyStatuses::cases()),
        ];
    }
}
