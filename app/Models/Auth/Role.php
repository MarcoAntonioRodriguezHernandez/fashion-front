<?php

namespace App\Models\Auth;

use App\Enums\Auth\PermissionTypes;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public static function attachPermissions(Role $role, array $permissions)
    {
        foreach ($permissions as $moduleId => $perms) {
            Permission::create([
                'role_id' => $role->id,
                'module_id' => $moduleId,
                'read' => $perms[PermissionTypes::READ->value] ?? false,
                'update' => $perms[PermissionTypes::UPDATE->value] ?? false,
                'create' => $perms[PermissionTypes::CREATE->value] ?? false,
            ]);
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, RoleUser::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
