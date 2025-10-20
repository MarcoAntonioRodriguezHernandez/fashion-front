<?php

namespace Database\Seeders\Auth;

use App\Enums\Auth\ModuleAliases;
use App\Models\Auth\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ModuleAliases::cases() as $module) {
            Module::factory()->create([
                'name' => Str::of($module->value)->title()->replace('-', ' '),
                'slug' => $module->value,
            ]);
        }
    }
}
