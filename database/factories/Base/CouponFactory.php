<?php

namespace Database\Factories\Base;

use App\Enums\CouponTypes;
use App\Enums\OrderSaleType;
use App\Models\Base\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $couponType = fake()->randomElement(CouponTypes::cases());
        $discount = match($couponType){
            CouponTypes::PERCENTAGE => fake()->numberBetween(0,60),
            CouponTypes::FIXED => fake()->numberBetween(1,2000),
        };

        $dateStart = Carbon::parse(fake()->date());
        $dateEnd = $dateStart->addDays(fake()->numberBetween(1,15));
        
        return [
            'code' => fake()->unique()->bothify('????-#####'),
            'uses_amount' => fake()->numberBetween(1, 5), 
            'category_id' => Category::all()->random()->id,
            'sale_type' => fake()->randomElement(OrderSaleType::cases()),
            'min_products' => fake()->numberBetween(1,5),
            'discount' => $discount,
            'coupon_type' => $couponType,
            'date_start' => $dateStart,
            'date_end' => $dateEnd,
            'status' => true,
        ];
    }
}
