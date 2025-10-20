<?php

namespace Database\Factories\Base;

use App\Models\Base\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'contact' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'phone' => fake()->numerify('55########'),
            'url' => fake()->url(),
            'country_id' => Country::all()->random()->id,
        ];
    }
}
