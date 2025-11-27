<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_number',
        'book_title',
        'book_type',
        'date_written',
        'description',
        'writer_name',
        'writer_rank',
        'writer_office',
        'status',
        'manager_comment',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date_written' => 'date',
        'approved_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function signatures()
    {
        return $this->morphMany(Signature::class, 'signable');
    }

    public function latestSignature()
    {
        return $this->morphOne(Signature::class, 'signable')->latestOfMany();
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'submitted' => 'مرسل للمدير',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
            'needs_modification' => 'يحتاج تعديل',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'submitted' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'needs_modification' => 'yellow',
            default => 'gray',
        };
    }

    public function getBookTypeLabelAttribute()
    {
        return match($this->book_type) {
            'incoming' => 'وارد',
            'outgoing' => 'صادر',
            'internal' => 'داخلي',
            'circular' => 'تعميم',
            'decision' => 'قرار',
            default => $this->book_type,
        };
    }

    public static function generateBookNumber()
    {
        $year = date('Y');
        $lastEntry = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNumber = $lastEntry ? ((int)substr($lastEntry->book_number, -4) + 1) : 1;
        return 'BK-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
