<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Record extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_number',
        'record_number',
        'military_id',
        'first_name',
        'second_name',
        'third_name',
        'fourth_name',
        'rank',
        'governorate',
        'station',
        'action_type',
        'ports',
        'notes',
        'round_date',
        'created_by',
    ];


    protected function casts(): array
    {
        return [
            'round_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('tracking_number', 'like', "%{$search}%")
              ->orWhere('record_number', 'like', "%{$search}%")
              ->orWhere('military_id', 'like', "%{$search}%")
              ->orWhere('first_name', 'like', "%{$search}%")
              ->orWhere('second_name', 'like', "%{$search}%")
              ->orWhere('third_name', 'like', "%{$search}%")
              ->orWhere('fourth_name', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByGovernorate($query, $governorate)
    {
        if ($governorate) {
            return $query->where('governorate', $governorate);
        }
        return $query;
    }

    public function scopeFilterByStation($query, $station)
    {
        if ($station) {
            return $query->where('station', $station);
        }
        return $query;
    }

    public function scopeFilterByRank($query, $rank)
    {
        if ($rank) {
            return $query->where('rank', $rank);
        }
        return $query;
    }

    public function scopeFilterByActionType($query, $actionType)
    {
        if ($actionType) {
            return $query->where('action_type', $actionType);
        }
        return $query;
    }

    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('round_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            return $query->where('round_date', '>=', $startDate);
        } elseif ($endDate) {
            return $query->where('round_date', '<=', $endDate);
        }
        return $query;
    }
}
