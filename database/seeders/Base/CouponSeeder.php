<?php

namespace Database\Seeders\Base;

use App\Models\Base\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::factory()->count(10)->create();
    }
}
