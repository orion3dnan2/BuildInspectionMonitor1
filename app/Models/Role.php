<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withTimestamps();
    }

    public function hasPermission(string $permissionKey): bool
    {
        return $this->permissions()->where('key', $permissionKey)->exists();
    }

    public function givePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('key', $permission)->first();
        }

        if ($permission && !$this->hasPermission($permission->key)) {
            $this->permissions()->attach($permission->id);
        }
    }

    public function revokePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('key', $permission)->first();
        }

        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }

    public static function getDefaultRoles(): array
    {
        return [
            [
                'name' => 'مدير النظام',
                'slug' => 'admin',
                'description' => 'صلاحيات كاملة على النظام',
                'is_system' => true,
                'level' => 100,
            ],
            [
                'name' => 'مشرف',
                'slug' => 'supervisor',
                'description' => 'صلاحيات إشرافية على النظام',
                'is_system' => true,
                'level' => 50,
            ],
            [
                'name' => 'مستخدم',
                'slug' => 'user',
                'description' => 'صلاحيات محدودة للعرض والبحث',
                'is_system' => true,
                'level' => 10,
            ],
        ];
    }

    public static function syncDefaultRoles(): void
    {
        foreach (self::getDefaultRoles() as $role) {
            self::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }

    public static function assignDefaultPermissions(): void
    {
        $allPermissions = Permission::all();
        
        $admin = self::where('slug', 'admin')->first();
        if ($admin) {
            $admin->syncPermissions($allPermissions->pluck('id')->toArray());
        }

        $supervisor = self::where('slug', 'supervisor')->first();
        if ($supervisor) {
            $supervisorPermissions = $allPermissions->filter(function ($permission) {
                $excludedModules = ['users', 'permissions'];
                return !in_array($permission->module, $excludedModules);
            });
            $supervisor->syncPermissions($supervisorPermissions->pluck('id')->toArray());
        }

        $user = self::where('slug', 'user')->first();
        if ($user) {
            $userPermissions = $allPermissions->filter(function ($permission) {
                $allowedActions = ['view', 'print'];
                $parts = explode('.', $permission->key);
                $action = end($parts);
                return in_array($action, $allowedActions);
            });
            $user->syncPermissions($userPermissions->pluck('id')->toArray());
        }
    }
}
