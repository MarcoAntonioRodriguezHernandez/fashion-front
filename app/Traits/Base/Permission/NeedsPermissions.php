<?php

namespace App\Traits\Base\Permission;

use App\Enums\Auth\PermissionTypes;
use Illuminate\Support\Facades\{
    Auth,
    Route,
};
use Illuminate\Support\Str;

trait NeedsPermissions
{

    /**
     * Get default permissions based on the name of the given route
     * 
     * @param string route
     * 
     * @return array
     */
    protected function getDefaultPermissions(string $route = null)
    {
        $route = Str::of($route ?? Route::currentRouteName());
        $permissions = []; // Stores the needed permissions to see this route

        if ($route->endsWith(['index'])) {
            $permissions[] = PermissionTypes::READ;
        } else if ($route->endsWith(['create', 'create.view'])) {
            $permissions[] = PermissionTypes::CREATE;
        } else if ($route->endsWith(['show'])) {
            $permissions[] = PermissionTypes::READ;
        } else if ($route->endsWith(['edit', 'edit.view'])) {
            $permissions[] = PermissionTypes::UPDATE;
        } else if ($route->endsWith(['delete'])) {
            $permissions[] = PermissionTypes::UPDATE;
        }

        if (empty($permissions)) {
            $permissions[] = PermissionTypes::READ;
        }

        return $permissions;
    }

    /**
     * Get permissions enum type from their alias
     * 
     * @param string $permissions List of minimum permissions to access. Should be composed by chars c(create), r(read), u(update)
     * 
     * @return array
     */
    protected function getPermissionsFromAliases($permissions)
    {
        return collect(str_split($permissions))
            ->map(fn ($v) => PermissionTypes::tryFromAlias($v))
            ->filter()
            ->values()
            ->mapWithKeys(fn ($p) => [$p->name => $p])
            ->toArray();
    }

    /**
     * Get the module we are actually on, based on route name
     * 
     * @return string
     */
    protected function getCurrentModule()
    {
        return Str::of(Route::currentRouteName())->betweenFirst('.', '.')->plural()->slug();
    }

    /**
     * Determinate if the current logged user has the permissions for the current route
     * 
     * @param array $permissions
     * 
     * @return bool
     */
    protected function currentUserHasPermission(...$permissions)
    {
        if (!Auth::check())
            return false;

        $module = $this->getCurrentModule();
        $permissions = count($permissions) ? $permissions : $this->getDefaultPermissions();

        return Auth::user()->hasAnyPermission($module, ...$permissions);
    }
}
