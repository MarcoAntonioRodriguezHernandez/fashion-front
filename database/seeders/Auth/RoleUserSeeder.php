<?php

namespace Database\Seeders\Auth;

use App\Models\Auth\RoleUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seeding random data
        RoleUser::factory()->times(20)->create();
    }
}
