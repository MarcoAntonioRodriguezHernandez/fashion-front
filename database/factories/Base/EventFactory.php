<?php

namespace Database\Factories\Base;

use App\Models\Base\EventType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_type_id' => EventType::all()->random()->id,
            'specification' => fake()->words(3, true),
            'scheduled_date' => fake()->date(),
        ];
    }
}
