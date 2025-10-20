<?php

namespace Database\Seeders\Base;

use App\Models\Base\CouponOrderMarketPlace;
use Illuminate\Database\Seeder;

class CouponOrderMarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CouponOrderMarketplace::factory()->count(10)->create();
    }
}
