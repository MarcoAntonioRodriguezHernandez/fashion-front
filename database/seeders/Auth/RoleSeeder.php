<?php

namespace Database\Seeders\Auth;

use App\Enums\Auth\ModuleAliases;
use App\Models\Auth\{
    Module,
    Role,
};
use App\Traits\Base\Permission\NeedsPermissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    use NeedsPermissions;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getDefaultRolesData();

        foreach ($data as $roleData) {
            $role = Role::factory()->create([
                'name' => $roleData->name,
                'slug' => Str::slug($roleData->name),
                'description' => $roleData->description,
            ]);

            $roleData->modules = array_is_list($roleData->modules) ?
                collect($roleData->modules)->mapWithKeys(fn ($m) => [$m->value => null])->toArray() :
                $roleData->modules;

            $modules = $this->getModulesFromSlugs(array_keys($roleData->modules));

            foreach ($modules as $module) {
                $permissions = $this->getPermissionsFromAliases($roleData->modules[$module->slug] ?? 'cru');
                $role->permissions()->create([
                    'module_id' => $module->id,
                    ...$permissions,
                ]);
            }
        }

        if (!config('services.conspiracy.sync_enabled')) {
            // Seeding predefined data if the database should not be synced
            Role::factory()->times(5)->create();
        }
    }

    private function getDefaultRolesData()
    {
        return [
            (object) [
                'name' => 'Super Administrador',
                'description' => 'Acceso completo a todo el sistema',
                'permissions' => null,
                'modules' => ModuleAliases::cases(),
            ],
            (object) [
                'name' => 'Inicial',
                'description' => 'Rol inicial para QA',
                'permissions' => null,
                'modules' => [
                    ModuleAliases::USER,
                    ModuleAliases::ROLE,
                ],
            ],
        ];
    }

    private function getModulesFromSlugs(array $slugs)
    {
        return Module::whereIn('slug', $slugs)->get();
    }
}
