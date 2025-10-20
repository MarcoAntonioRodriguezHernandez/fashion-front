<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Category,
    Characteristic,
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\CategoryCharacteristic>
 */
class CategoryCharacteristicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::all()->random()->id,
            'characteristic_id' => Characteristic::all()->random()->id,
        ];
    }
}
