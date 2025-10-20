<?php

namespace Database\Seeders\Base;

use App\Models\Base\InvoiceFile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InvoiceFile::factory()->times(20)->create();
    }
}
