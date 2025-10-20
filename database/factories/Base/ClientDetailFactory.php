<?php

namespace Database\Factories\Base;

use App\Enums\Genders;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\ClientDetail>
 */
class ClientDetailFactory extends Factory
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
            'date_of_birth' => fake()->randomElement([null, fake()->date()]),
            'gender' => fake()->randomElement(Genders::cases()),
        ];
    }
}
