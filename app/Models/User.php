<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'permissions',
        'system_access',
        'rank',
        'office',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'permissions' => 'array',
            'system_access' => 'array',
        ];
    }

    public function records(): HasMany
    {
        return $this->hasMany(Record::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->whereNull('read_at')->orderBy('created_at', 'desc');
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }

    public function getPendingBooksCountAttribute(): int
    {
        if (!$this->can('books.approve')) {
            return 0;
        }
        return \App\Models\BookEntry::where('status', 'submitted')->count();
    }

    public function getPendingDocumentsCountAttribute(): int
    {
        return \App\Models\Document::where('assigned_to', $this->id)
            ->whereIn('status', ['pending_review', 'pending_approval', 'needs_modification'])
            ->count();
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withTimestamps();
    }

    public function userPermissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
            ->withPivot('granted')
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists() || $this->role === $roleSlug;
    }

    public function hasAnyRole(array $roleSlugs): bool
    {
        foreach ($roleSlugs as $roleSlug) {
            if ($this->hasRole($roleSlug)) {
                return true;
            }
        }
        return false;
    }

    public function can($ability, $arguments = []): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (str_contains($ability, '.')) {
            return $this->hasPermissionKey($ability);
        }

        return parent::can($ability, $arguments);
    }

    public function hasPermissionKey(string $permissionKey): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $directPermission = $this->userPermissions()
            ->where('key', $permissionKey)
            ->wherePivot('granted', true)
            ->exists();
        
        if ($directPermission) {
            return true;
        }

        $deniedPermission = $this->userPermissions()
            ->where('key', $permissionKey)
            ->wherePivot('granted', false)
            ->exists();
        
        if ($deniedPermission) {
            return false;
        }

        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionKey)) {
                return true;
            }
        }

        $legacyRole = Role::where('slug', $this->role)->first();
        if ($legacyRole && $legacyRole->hasPermission($permissionKey)) {
            return true;
        }

        return false;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (str_contains($permission, '.')) {
            return $this->hasPermissionKey($permission);
        }
        
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        if ($this->isAdmin()) {
            return Permission::all();
        }

        $rolePermissions = collect();
        foreach ($this->roles as $role) {
            $rolePermissions = $rolePermissions->merge($role->permissions);
        }

        $legacyRole = Role::where('slug', $this->role)->first();
        if ($legacyRole) {
            $rolePermissions = $rolePermissions->merge($legacyRole->permissions);
        }

        $directPermissions = $this->userPermissions()
            ->wherePivot('granted', true)
            ->get();

        $deniedPermissions = $this->userPermissions()
            ->wherePivot('granted', false)
            ->pluck('id');

        return $rolePermissions
            ->merge($directPermissions)
            ->unique('id')
            ->reject(fn($p) => $deniedPermissions->contains($p->id));
    }

    public function givePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('key', $permission)->first();
        }

        if ($permission) {
            $this->userPermissions()->syncWithoutDetaching([
                $permission->id => ['granted' => true]
            ]);
        }
    }

    public function revokePermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('key', $permission)->first();
        }

        if ($permission) {
            $this->userPermissions()->detach($permission->id);
        }
    }

    public function denyPermission(string|Permission $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('key', $permission)->first();
        }

        if ($permission) {
            $this->userPermissions()->syncWithoutDetaching([
                $permission->id => ['granted' => false]
            ]);
        }
    }

    public function assignRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }
    }

    public function removeRole(string|Role $role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    public function syncRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->hasPermission('manage_users') || $this->hasPermissionKey('users.manage');
    }

    public function canManageSettings(): bool
    {
        return $this->isAdmin() || $this->hasPermission('manage_settings') || $this->hasPermissionKey('settings.manage');
    }

    public function canCreateRecords(): bool
    {
        return $this->isAdmin() || $this->hasPermission('create_records') || $this->hasPermissionKey('records.create') || $this->hasPermissionKey('data_entry.create');
    }

    public function canEditRecords(): bool
    {
        return $this->isAdmin() || $this->hasPermission('edit_records') || $this->hasPermissionKey('records.update') || $this->hasPermissionKey('data_entry.update');
    }

    public function canDeleteRecords(): bool
    {
        return $this->isAdmin() || $this->hasPermission('delete_records') || $this->hasPermissionKey('records.delete') || $this->hasPermissionKey('data_entry.delete');
    }

    public function canAccessDataEntry(): bool
    {
        return $this->isAdmin() || $this->hasPermissionKey('data_entry.view') || $this->hasPermissionKey('data_entry.create');
    }

    public function canImport(): bool
    {
        return $this->isAdmin() || $this->hasPermission('import_data') || $this->hasPermissionKey('import.import');
    }

    public function canViewRecords(): bool
    {
        return $this->hasPermissionKey('records.view') || $this->hasPermission('view_records') || true;
    }

    public function canSearch(): bool
    {
        return $this->hasPermissionKey('search.view') || true;
    }

    public function canViewReports(): bool
    {
        return $this->hasPermissionKey('reports.view') || true;
    }

    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'مدير',
            'supervisor' => 'مشرف',
            'user' => 'مستخدم',
            default => 'مستخدم'
        };
    }

    public static function availablePermissions(): array
    {
        return [
            'manage_users' => 'إدارة المستخدمين',
            'manage_settings' => 'إدارة الإعدادات (المخافر والمنافذ)',
            'create_records' => 'إضافة سجلات جديدة',
            'edit_records' => 'تعديل السجلات',
            'delete_records' => 'حذف السجلات',
            'import_data' => 'استيراد البيانات',
        ];
    }

    public static function availableSystems(): array
    {
        return [
            'block_system' => 'نظام الحظر والتفتيش',
            'admin_system' => 'النظام الإداري',
        ];
    }

    public function hasSystemAccess(string $system): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $systemAccess = $this->system_access ?? [];
        return in_array($system, $systemAccess);
    }

    public function canAccessBlockSystem(): bool
    {
        return $this->hasSystemAccess('block_system');
    }

    public function canAccessAdminSystem(): bool
    {
        return $this->hasSystemAccess('admin_system');
    }

    public function getSystemAccessLabelsAttribute(): array
    {
        $systems = self::availableSystems();
        $access = $this->system_access ?? [];
        $labels = [];
        
        foreach ($access as $key) {
            if (isset($systems[$key])) {
                $labels[] = $systems[$key];
            }
        }
        
        return $labels;
    }
}
