<?php

namespace Database\Factories\Base;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\UserAddress>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'interior_number' => fake()->randomElement([null, fake()->numberBetween(1, 15)]),
            'external_number' => fake()->randomElement([null, fake()->numberBetween(1, 15)]),
            'street' => fake()->streetAddress(),
            'colony' => fake()->words(3, true),
            'city' => fake()->city(),
            'state' => fake()->words(2, true),
            'zip_code' => fake()->numerify('#####'),
        ];
    }
}
