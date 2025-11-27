<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    public function getIconAttribute(): string
    {
        return match($this->type) {
            'book_submitted' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'book_approved' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'book_rejected' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'book_needs_modification' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'document_submitted' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'document_approved' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'document_rejected' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
            'document_needs_modification' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'document_assigned' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
            default => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->type) {
            'book_approved', 'document_approved' => 'text-emerald-500',
            'book_rejected', 'document_rejected' => 'text-red-500',
            'book_needs_modification', 'document_needs_modification' => 'text-amber-500',
            'book_submitted', 'document_submitted' => 'text-sky-500',
            'document_assigned' => 'text-purple-500',
            default => 'text-slate-500',
        };
    }

    public static function createBookNotification(BookEntry $book, string $action, ?User $actor = null): void
    {
        $users = collect();
        
        switch ($action) {
            case 'submitted':
                $users = User::whereIn('role', ['admin', 'supervisor'])->get();
                $title = 'كتاب جديد للمراجعة';
                $message = "تم إرسال كتاب جديد \"{$book->book_title}\" للمراجعة";
                break;
            case 'approved':
                if ($book->created_by) {
                    $users->push(User::find($book->created_by));
                }
                $title = 'تم اعتماد الكتاب';
                $message = "تم اعتماد الكتاب \"{$book->book_title}\"";
                break;
            case 'rejected':
                if ($book->created_by) {
                    $users->push(User::find($book->created_by));
                }
                $title = 'تم رفض الكتاب';
                $message = "تم رفض الكتاب \"{$book->book_title}\"" . ($book->manager_comment ? ": {$book->manager_comment}" : '');
                break;
            case 'needs_modification':
                if ($book->created_by) {
                    $users->push(User::find($book->created_by));
                }
                $title = 'الكتاب يحتاج تعديل';
                $message = "الكتاب \"{$book->book_title}\" يحتاج إلى تعديل" . ($book->manager_comment ? ": {$book->manager_comment}" : '');
                break;
        }

        foreach ($users->filter() as $user) {
            if ($actor && $user->id === $actor->id) {
                continue;
            }
            
            self::create([
                'user_id' => $user->id,
                'type' => 'book_' . $action,
                'title' => $title,
                'message' => $message,
                'notifiable_type' => BookEntry::class,
                'notifiable_id' => $book->id,
                'data' => [
                    'book_number' => $book->book_number,
                    'actor_id' => $actor?->id,
                    'actor_name' => $actor?->name,
                ],
            ]);
        }
    }

    public static function createDocumentNotification(Document $document, string $action, ?User $actor = null): void
    {
        $users = collect();
        
        switch ($action) {
            case 'submitted':
            case 'pending_review':
                $users = User::whereIn('role', ['admin', 'supervisor'])->get();
                $title = 'مستند جديد للمراجعة';
                $message = "تم إرسال مستند جديد \"{$document->title}\" للمراجعة";
                $action = 'submitted';
                break;
            case 'approved':
                if ($document->created_by) {
                    $users->push(User::find($document->created_by));
                }
                $title = 'تم اعتماد المستند';
                $message = "تم اعتماد المستند \"{$document->title}\"";
                break;
            case 'rejected':
                if ($document->created_by) {
                    $users->push(User::find($document->created_by));
                }
                $title = 'تم رفض المستند';
                $message = "تم رفض المستند \"{$document->title}\"" . ($document->rejection_reason ? ": {$document->rejection_reason}" : '');
                break;
            case 'needs_modification':
                if ($document->created_by) {
                    $users->push(User::find($document->created_by));
                }
                $title = 'المستند يحتاج تعديل';
                $message = "المستند \"{$document->title}\" يحتاج إلى تعديل" . ($document->modification_notes ? ": {$document->modification_notes}" : '');
                break;
            case 'assigned':
                if ($document->assigned_to) {
                    $users->push(User::find($document->assigned_to));
                }
                $title = 'تم تعيين مستند لك';
                $message = "تم تعيين المستند \"{$document->title}\" لك للمراجعة";
                break;
        }

        foreach ($users->filter() as $user) {
            if ($actor && $user->id === $actor->id) {
                continue;
            }
            
            self::create([
                'user_id' => $user->id,
                'type' => 'document_' . $action,
                'title' => $title,
                'message' => $message,
                'notifiable_type' => Document::class,
                'notifiable_id' => $document->id,
                'data' => [
                    'document_number' => $document->document_number,
                    'actor_id' => $actor?->id,
                    'actor_name' => $actor?->name,
                ],
            ]);
        }
    }
}
