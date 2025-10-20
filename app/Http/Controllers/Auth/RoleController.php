<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Auth\PermissionTypes;
use App\Enums\CrudAction;
use App\Http\Controllers\Common\GenericCrudProvider;
use App\Http\Requests\Auth\Role\{
    PostRequest,
    PutRequest,
};
use App\Models\Auth\{
    Module,
    Permission,
    Role,
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\SUpport\Str;

class RoleController extends GenericCrudProvider
{
    protected string $modelClass = Role::class;

    protected string $indexView = 'auth.roles.index';
    protected string $showView = 'auth.roles.show';
    protected string $createView = 'auth.roles.create';
    protected string $editView = 'auth.roles.edit';

    protected int $pageSize = 9;

    protected function rules(): array
    {
        return [
            CrudAction::CREATE->value => (new PostRequest())->rules(),
            CrudAction::UPDATE->value => (new PutRequest())->rules(),
        ];
    }

    protected function pushCreateView()
    {
        $modules = Module::all();

        return compact('modules');
    }

    protected function pushEditView(Model $model)
    {
        $modules = Module::all();
        $model->permissions = $model->permissions()->get()->mapWithKeys(fn (Permission $permission) => [
            $permission->module_id => [
                PermissionTypes::READ->value => $permission->read,
                PermissionTypes::UPDATE->value => $permission->update,
                PermissionTypes::CREATE->value => $permission->create,
            ],
        ]);

        return compact('modules');
    }

    protected function beforeCreate(array &$validatedData, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:roles,slug',
        ]);

        return $request->all();
    }

    protected function beforeUpdate(array &$validatedData, Model $model, Request $request): ?array
    {
        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        $request->validate([
            'slug' => 'required|string|unique:roles,slug,' . $model->id,
        ]);

        return $request->all();
    }

    protected function afterUpdate(Model $model, Request $request): ?array
    {
        $model->permissions()->delete(); // Remove all the old permissions

        Role::attachPermissions($model, $request->permissions);

        return null;
    }

    protected function afterCreate(Model $model, Request $request): ?array
    {
        Role::attachPermissions($model, $request->permissions);

        return null;
    }
}
