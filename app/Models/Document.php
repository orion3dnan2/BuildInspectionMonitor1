<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'title',
        'type',
        'content',
        'department_id',
        'created_by',
        'status',
        'priority',
        'assigned_to',
        'modification_notes',
        'rejection_reason',
        'approved_at',
        'approved_by',
        'signature_data',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            $lastId = static::withTrashed()->max('id') ?? 0;
            $nextId = $lastId + 1;
            $document->document_number = 'DOC-' . date('Y') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        });
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(DocumentWorkflow::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'pending_review' => 'قيد المراجعة',
            'pending_approval' => 'قيد الاعتماد',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'needs_modification' => 'يحتاج تعديل',
            default => $this->status,
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'letter' => 'كتاب',
            'memo' => 'مذكرة',
            'report' => 'تقرير',
            'decision' => 'قرار',
            'circular' => 'تعميم',
            'other' => 'أخرى',
            default => $this->type,
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'منخفضة',
            'normal' => 'عادية',
            'high' => 'عالية',
            'urgent' => 'عاجلة',
            default => $this->priority,
        };
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('document_number', 'like', "%{$search}%")
              ->orWhere('title', 'like', "%{$search}%");
        });
    }
}
