<?php

namespace Database\Factories\Base;

use App\Models\Marketplace\OrderMarketplace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // We get a random OrderMarketplace or create one if none exists.
        $orderMarketplace = OrderMarketplace::inRandomOrder()->first() ?? OrderMarketplace::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'text' => fake()->text(),
            'link' => fake()->url(),
            'date' => fake()->dateTime(),
            'order_marketplace_id' => $orderMarketplace->id, 
            'user_id' => $user->id,
        ];
    }
}
