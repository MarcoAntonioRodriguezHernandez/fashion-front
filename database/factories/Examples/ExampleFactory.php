<?php

namespace Database\Factories\Examples;

use App\Models\Examples\ExampleType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Examples\Example>
 */
class ExampleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $name = fake()->name;
        $slug = Str::slug($name);

        return [
            'example_type_id' => ExampleType::all()->random()->id,
            'name' => $name,
            'slug' => $slug,
            'image' => fake()->imageUrl(640, 480, 'business', true),
            'description' => fake()->paragraph,
            'creation' => fake()->dateTimeBetween('-1 year', 'now'),
            'value' => fake()->randomFloat(2, 0, 1000),
            'quantity' => fake()->numberBetween(0, 100),
            'status' => fake()->boolean,
        ];
    }
}
