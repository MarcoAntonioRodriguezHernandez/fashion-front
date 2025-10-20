<?php

namespace Database\Seeders\Base;

use App\Enums\PaymentStatuses;
use App\Models\Base\{
    Invoice,
    PaymentType,
};
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Invoice::factory()->create([
            'buyer' => User::first()->id,
            'payment_status' => PaymentStatuses::APPROVED,
            'issuance_date' => now(),
            'payment_type_id' => PaymentType::first()->id,
        ]);

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding predefined data if the database should not be synced
            Invoice::factory()->times(20)->create();
        }
    }
}
