<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentWorkflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'from_user_id',
        'to_user_id',
        'action',
        'comments',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'submit' => 'إرسال',
            'review' => 'مراجعة',
            'approve' => 'اعتماد',
            'reject' => 'رفض',
            'request_modification' => 'طلب تعديل',
            'modify' => 'تعديل',
            'forward' => 'تحويل',
            default => $this->action,
        };
    }
}
