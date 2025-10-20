<?php

namespace Database\Factories\Base;

use App\Models\Base\{
    Designer,
    Provider,
};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Base\DesignerProvider>
 */
class DesignerProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'designer_id' => Designer::all()->random()->id,
            'provider_id' => Provider::all()->random()->id,
        ];
    }
}
