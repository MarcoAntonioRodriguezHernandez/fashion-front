<?php

namespace Database\Factories\Base;

use App\Models\Base\ClientDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientCredit>
 */
class ClientCreditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_detail_id' => ClientDetail::all()->random()->id,
            'amount' => fake()->numberBetween(2, 10000),
            'expiration_date' => now()->addMonths(config('common.client_credit_validity')),

        ];
    }
}
