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
        'original_file_path',
        'pdf_path',
        'signed_pdf_path',
        'is_signed',
        'signed_at',
        'signed_by',
        'archived_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'signed_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_signed' => 'boolean',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_SIGNED = 'signed';
    const STATUS_APPROVED = 'approved';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_PENDING_APPROVAL = 'pending_approval';
    const STATUS_REJECTED = 'rejected';
    const STATUS_NEEDS_MODIFICATION = 'needs_modification';

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

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function workflows(): HasMany
    {
        return $this->hasMany(DocumentWorkflow::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'under_review' => 'قيد المراجعة',
            'pending_review' => 'قيد المراجعة',
            'signed' => 'موقّع',
            'pending_approval' => 'قيد الاعتماد',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'needs_modification' => 'يحتاج تعديل',
            'archived' => 'مؤرشف',
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

    public function getViewablePdfPath(): ?string
    {
        if ($this->signed_pdf_path) {
            return $this->signed_pdf_path;
        }
        if ($this->pdf_path) {
            return $this->pdf_path;
        }
        if ($this->file_path && strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)) === 'pdf') {
            return $this->file_path;
        }
        return null;
    }

    public function canBeSigned(): bool
    {
        return !$this->is_signed && 
               in_array($this->status, ['draft', 'under_review', 'pending_review', 'pending_approval']) &&
               $this->getViewablePdfPath() !== null;
    }

    public function canBeApproved(): bool
    {
        return $this->is_signed && 
               in_array($this->status, ['signed', 'pending_approval']);
    }

    public function isWordDocument(): bool
    {
        $path = $this->original_file_path ?? $this->file_path;
        if (!$path) return false;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($ext, ['doc', 'docx']);
    }

    public function isPdfDocument(): bool
    {
        $path = $this->original_file_path ?? $this->file_path;
        if (!$path) return false;
        return strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';
    }

    public function getOriginalFilePath(): ?string
    {
        return $this->original_file_path ?? $this->file_path;
    }

    public function hasViewableFile(): bool
    {
        return $this->getOriginalFilePath() !== null || $this->getViewablePdfPath() !== null;
    }
}
