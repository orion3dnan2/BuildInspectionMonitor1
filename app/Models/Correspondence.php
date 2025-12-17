<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Correspondence extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'title',
        'type',
        'from_department',
        'to_department',
        'document_date',
        'subject',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'file_size' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function signatures(): MorphMany
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'incoming' => 'وارد',
            'outgoing' => 'صادر',
            default => $this->type,
        };
    }

    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'new' => 'جديد',
            'reviewed' => 'تمت المراجعة',
            'completed' => 'مكتمل',
            'archived' => 'مؤرشف',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'sky',
            'reviewed' => 'amber',
            'completed' => 'emerald',
            'archived' => 'slate',
            default => 'slate',
        };
    }

    public function getFileIconAttribute(): string
    {
        if (!$this->file_type) {
            return 'document';
        }

        return match(true) {
            str_contains($this->file_type, 'pdf') => 'pdf',
            str_contains($this->file_type, 'word') || str_contains($this->file_type, 'doc') => 'word',
            str_contains($this->file_type, 'image') => 'image',
            str_contains($this->file_type, 'excel') || str_contains($this->file_type, 'spreadsheet') => 'excel',
            default => 'document',
        };
    }

    public function isWord(): bool
    {
        if (!$this->file_type) return false;
        return str_contains($this->file_type, 'word') || 
               str_contains($this->file_type, 'msword') ||
               str_contains($this->file_type, 'document');
    }

    public function isPdf(): bool
    {
        if (!$this->file_type) return false;
        return str_contains($this->file_type, 'pdf');
    }

    public function isImage(): bool
    {
        if (!$this->file_type) return false;
        return str_contains($this->file_type, 'image');
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) return '-';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function generateDocumentNumber(string $type): string
    {
        $prefix = $type === 'incoming' ? 'IN' : 'OUT';
        $year = date('Y');
        $lastDoc = self::where('type', $type)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = 1;
        if ($lastDoc) {
            $parts = explode('-', $lastDoc->document_number);
            if (count($parts) >= 3) {
                $sequence = intval($parts[2]) + 1;
            }
        }
        
        return sprintf('%s-%s-%04d', $prefix, $year, $sequence);
    }

    public function scopeIncoming($query)
    {
        return $query->where('type', 'incoming');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', 'outgoing');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('document_number', 'like', "%{$search}%")
              ->orWhere('title', 'like', "%{$search}%")
              ->orWhere('subject', 'like', "%{$search}%")
              ->orWhere('from_department', 'like', "%{$search}%")
              ->orWhere('to_department', 'like', "%{$search}%");
        });
    }
}
