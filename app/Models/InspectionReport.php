<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'record_number',
        'outgoing_number',
        'officer_name',
        'rank',
        'office_name',
        'inspection_date',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('record_number', 'like', "%{$search}%")
                  ->orWhere('officer_name', 'like', "%{$search}%")
                  ->orWhere('office_name', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterByDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('inspection_date', $date);
        }
        return $query;
    }

    public function scopeFilterByOffice($query, $office)
    {
        if ($office) {
            return $query->where('office_name', 'like', "%{$office}%");
        }
        return $query;
    }
}
