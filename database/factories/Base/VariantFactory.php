<?php

namespace Database\Factories\Base;

use App\Models\Base\Size;
use App\Models\Base\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $size = Size::all()->random();
        $color = Color::all()->random();
        $code = $size->slug . '-' . $color->slug . '-' . $this->faker->numerify("##");

        return [
            'size_id' => $size->id, 
            'color_id' => $color->id,
            'code' => $code,
            'status' => 1,
        ];
    }
}
