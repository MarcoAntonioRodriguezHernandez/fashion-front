<?php

namespace Database\Factories\Base;

use App\Enums\NotificationTypes;
use App\Models\Base\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\EmployeeDetail>
 */
class EmployeeDetailFactory extends Factory
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
            'store_id' => Store::all()->random()->id,
            'notifications_allowed' => join('-', collect(fake()->randomElements(NotificationTypes::cases()))->map(fn($type) => $type->value)->toArray()),
        ];
    }
}
