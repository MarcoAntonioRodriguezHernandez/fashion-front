<?php

namespace Database\Seeders\Base;

use App\Models\Base\ClientCredit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientCredit::factory()->count(10)->create();
    }
}
