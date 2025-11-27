<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_number',
        'first_name',
        'second_name',
        'third_name',
        'fourth_name',
        'civil_id',
        'phone',
        'email',
        'department_id',
        'job_title',
        'rank',
        'hire_date',
        'birth_date',
        'gender',
        'marital_status',
        'address',
        'salary',
        'annual_leave_balance',
        'sick_leave_balance',
        'status',
        'user_id',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hire_date' => 'date',
            'birth_date' => 'date',
            'salary' => 'decimal:3',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->second_name,
            $this->third_name,
            $this->fourth_name,
        ])));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('employee_number', 'like', "%{$search}%")
              ->orWhere('civil_id', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('second_name', 'like', "%{$search}%")
              ->orWhere('third_name', 'like', "%{$search}%")
              ->orWhere('fourth_name', 'like', "%{$search}%");
        });
    }
}
