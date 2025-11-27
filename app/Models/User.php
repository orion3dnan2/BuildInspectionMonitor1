<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function canManageUsers(): bool
    {
        return $this->isAdmin() || $this->hasPermission('manage_users');
    }

    public function canManageSettings(): bool
    {
        return $this->isAdmin() || $this->hasPermission('manage_settings');
    }

    public function canCreateRecords(): bool
    {
        return $this->isAdmin() || $this->hasPermission('create_records');
    }

    public function canEditRecords(): bool
    {
        return $this->isAdmin() || $this->hasPermission('edit_records');
    }

    public function canDeleteRecords(): bool
    {
        return $this->isAdmin() || $this->hasPermission('delete_records');
    }

    public function canImport(): bool
    {
        return $this->isAdmin() || $this->hasPermission('import_data');
    }

    public function canViewRecords(): bool
    {
        return true;
    }

    public function canSearch(): bool
    {
        return true;
    }

    public function canViewReports(): bool
    {
        return true;
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
}
