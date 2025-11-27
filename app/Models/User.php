<?php

namespace App\Models;

use App\Enums\Auth\{
    ModuleAliases,
    PermissionTypes,
};
use App\Enums\Auth\RoleAliases;
use App\Enums\NotificationTypes;
use App\Models\Auth\{
    Module,
    Permission,
    Role,
};
use App\Models\Base\{
    ClientDetail,
    CouponOrderMarketPlace,
    EmployeeDetail,
    Notification,
    UserAddress,
};
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'password',
        'status',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get user's full name
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    /**
     * Get user's permissions
     */
    public function getPermissionsAttribute()
    {
        return Permission::query()
            ->select([
                'modules.slug',
                'read as ' . PermissionTypes::READ->value,
                'update as ' . PermissionTypes::UPDATE->value,
                'create as ' . PermissionTypes::CREATE->value,
            ])
            ->join('modules', 'permissions.module_id', '=', 'modules.id')
            ->whereIn('role_id', $this->roles()->pluck('roles.id'))
            ->get()
            ->groupBy('slug')
            ->map(function ($permissionsGroup, $slug) {
                return $permissionsGroup->map(fn($val) => collect($val->getAttributes())->filter()->keys())
                    ->flatten()
                    ->unique()
                    ->reject('slug')
                    ->values();
            });
    }

    /**
     * Determine if this user has any of the requested permissions
     *
     * @param int|string $moduleKey The id or slug of the module
     * @param array<string> $permissions The requested permissions. One of read, update, or create
     *
     * @return bool true if the user has any of these permissions, or false otherwise
     */
    public function hasAnyPermission(int|string|ModuleAliases $moduleKey, PermissionTypes ...$permissions)
    {
        if (empty($permissions)) { // If there is no permission required
            return true;
        }

        if ($moduleKey instanceof ModuleAliases) {
            $moduleKey = $moduleKey->value;
        }

        $module = Module::where('slug', $moduleKey)->first();

        if ($module == null) {
            return true;
        }

        return Permission::query()
            ->select([
                'read as ' . PermissionTypes::READ->value,
                'update as ' . PermissionTypes::UPDATE->value,
                'create as ' . PermissionTypes::CREATE->value,
            ])
            ->whereIn('role_id', $this->roles()->pluck('roles.id'))
            ->where('module_id', $module->id)
            ->get()
            ->map(fn ($val) => collect($val->getAttributes())->filter()->keys())
            ->flatten()
            ->intersect(array_map(fn ($perm) => $perm->value, $permissions))
            ->isNotEmpty();
    }

    public function hasAnyRole(array|string|RoleAliases $roles): bool {
        if (!is_array($roles)) {
        $roles = [$roles];
        }

        $roles = array_map(function ($role) {
            if ($role instanceof RoleAliases) {
                return $role->value;
            } elseif (is_object($role) && method_exists($role, '__toString')) {
                return (string) $role;
            }
            return $role;
        }, $roles);

        $userRoles = $this->roles()->pluck('slug')->toArray();
        return !empty(array_intersect($roles, $userRoles));
    }

    public function scopeEmployees(Builder $query)
    {
        return $query->whereHas('employeeDetail');
    }

    public function scopeClients(Builder $query)
    {
        return $query->whereHas('clientDetail');
    }

    public function scopeSearch(Builder $query, string $search = null)
    {
        return $query->where('email', 'LIKE', '%' . $search . '%');
    }

    public function scopeNotifiedFor(Builder $query, NotificationTypes $type)
    {
        return $query->whereHas('employeeDetail', fn ($q) => $q->where('notifications_allowed', 'LIKE', '%' . $type->value . '%'));
    }

    /**
     * Get user's notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function clientDetail()
    {
        return $this->hasOne(ClientDetail::class);
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }

    public function userAddresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function getSrcImgUrlAttribute()
    {
        return asset('storage/images/images/' . $this->photo);
    }

    public function couponOrderMarketplace()
    {
        return $this->hasMany(CouponOrderMarketplace::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
