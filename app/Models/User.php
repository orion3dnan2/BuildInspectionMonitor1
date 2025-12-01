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
            'password' => 'hashed',
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

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInspector(): bool
    {
        return $this->role === 'inspector';
    }

    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'مدير',
            'inspector' => 'مفتش',
            default => 'مفتش'
        };
    }

    public static function availableRoles(): array
    {
        return [
            'admin' => 'مدير',
            'inspector' => 'مفتش',
        ];
    }
}
