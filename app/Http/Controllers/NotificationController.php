<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->recent(30)
            ->take(20)
            ->get();

        return response()->json([
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => $this->getNotificationUrl($notification),
                ];
            }),
            'unread_count' => $request->user()->unread_notifications_count,
        ]);
    }

    public function counts(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'unread_notifications' => $user->unread_notifications_count,
            'pending_books' => $user->pending_books_count,
            'pending_documents' => $user->pending_documents_count,
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    protected function getNotificationUrl(Notification $notification): ?string
    {
        if (!$notification->notifiable_type || !$notification->notifiable_id) {
            return null;
        }

        if ($notification->notifiable_type === 'App\Models\BookEntry') {
            return route('books.show', $notification->notifiable_id);
        }

        if ($notification->notifiable_type === 'App\Models\Document') {
            return route('admin.documents.show', $notification->notifiable_id);
        }

        return null;
    }
}
