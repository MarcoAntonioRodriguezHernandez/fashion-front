<?php

namespace Database\Factories\Base;

use App\Enums\PaymentStatuses;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => fake()->unique()->bothify('##??##?#'),
            'buyer' => User::all()->random()->id,
            'payment_status' => fake()->randomElement(PaymentStatuses::cases()),
            'issuance_date' => fake()->dateTime(),
            'payment_type_id' => fake()->randomElement([1, 2, 3]),
            'exchange_rate' => fake()->numberBetween(16, 17),
            'invoice_file' => InvoiceFileFactory::new(),
        ];
    }
}
