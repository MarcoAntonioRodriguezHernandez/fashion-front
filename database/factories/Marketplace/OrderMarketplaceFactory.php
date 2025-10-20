<?php

namespace Database\Factories\Marketplace;

use App\Enums\{
    FoundByMethods,
    OrderStatuses,
};
use App\Models\Base\{
    Marketplace,
    Store,
};
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderMarketplace>
 */
class OrderMarketplaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        $amount = fake()->randomFloat(2, 100, 4000);
        $discount = fake()->randomFloat(2, 0, 1);

        return [
            'employee_id' => User::employees()->inRandomOrder()->first()->id,
            'client_id' => User::clients()->inRandomOrder()->first()->id,
            'code' => $faker->unique()->bothify('??##??##??'),
            'marketplace_id' => Marketplace::all()->random()->id,
            'amount' => $amount,
            'discount' => $discount,
            'surcharge' => 0,
            'store_id' => Store::all()->random()->id,
            'number_products' => fake()->randomNumber(1),
            'status' => fake()->randomElement(OrderStatuses::cases()),
            'date_canceled' => fake()->dateTime(),
            'found_by' => fake()->randomElement(FoundByMethods::cases()),
            'event_id' => null,
        ];
    }
}
