<?php

namespace Database\Seeders\Base;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Base\PaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentType::factory()->create([
            'name' => 'Crédito de cliente',
            'slug' => 'credito-de-cliente',
        ]);
        PaymentType::factory()->create([
            'name' => 'Desconocido',
            'slug' => 'desconocido',
        ]);

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding predefined data if the database should not be synced
            foreach ($this->name as $name) {
                PaymentType::create([
                    'name' => $name,
                    'slug' => Str::slug($name),
                ]);
            }
        }
    }

    private $name = [
        'Fisico',
        'Tarjeta Credito / Debito',
        'Deposito',
        'Transferencia'
    ];
}
