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

    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canManageSettings(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function canCreateRecords(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function canEditRecords(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function canDeleteRecords(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function canImport(): bool
    {
        return $this->isAdmin() || $this->isSupervisor();
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
}
