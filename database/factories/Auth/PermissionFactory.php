<?php

namespace Database\Factories\Auth;

use App\Models\Auth\{
    Module,
    Role,
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auth\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => Role::all()->random()->id,
            'module_id' => Module::all()->random()->id,
            'read' => fake()->boolean(),
            'update' => fake()->boolean(),
            'create' => fake()->boolean(),
        ];
    }
}
