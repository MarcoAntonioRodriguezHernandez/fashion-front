<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        Permission::factory()->times(20)->create();
    }
}
