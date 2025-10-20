<?php

namespace Database\Seeders;

use App\Models\Base\{
    ClientDetail,
    EmployeeDetail,
    UserAddress,
};
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->employees as $user) {
            $user = User::factory()
                ->has(EmployeeDetail::factory())
                ->create($user);

            $user->roles()->sync([1]);
        }

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding random data if the database should not be synced
            User::factory()
                ->has(EmployeeDetail::factory())
                ->count(10)
                ->create();

            User::factory()
                ->has(ClientDetail::factory())
                ->has(UserAddress::factory())
                ->count(30)
                ->create();
        }
    }

    private $employees = [
        [
            'name' => 'QA User',
            'last_name' => 'Testing',
            'email_verified_at' => '2024-02-14 02:02:48',
            'email' => 'qa@fashion.com',
            'phone' => '5512341234',
            'photo' => null,
            'status' => 1,
        ],
        [
            'name' => 'Administracion',
            'last_name' => 'Conspiracion',
            'email_verified_at' => '2024-02-14 02:02:48',
            'email' => 'admin_cospiracion@cmoda.com',
            'photo' => null,
            'phone' => '0000000000',
            'status' => 1,
        ],
    ];
}
