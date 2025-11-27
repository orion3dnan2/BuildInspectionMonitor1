<?php

namespace App\Http\Controllers;

use App\Models\BookEntry;
use App\Models\Notification;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookEntryController extends Controller
{
    protected function authorizeOwnerOrManager(BookEntry $book): bool
    {
        $user = Auth::user();
        return $user->id === $book->created_by || in_array($user->role, ['admin', 'supervisor']);
    }

    protected function authorizeOwner(BookEntry $book): bool
    {
        return Auth::id() === $book->created_by;
    }

    public function index(Request $request)
    {
        $query = BookEntry::with(['creator', 'approver']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('book_number', 'like', "%{$search}%")
                  ->orWhere('book_title', 'like', "%{$search}%")
                  ->orWhere('writer_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('book_type')) {
            $query->where('book_type', $request->book_type);
        }
        
        $user = Auth::user();
        if ($user->role === 'user') {
            $query->where('created_by', $user->id);
        }
        
        $entries = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('books.index', compact('entries'));
    }

    public function create()
    {
        $bookNumber = BookEntry::generateBookNumber();
        return view('books.create', compact('bookNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_number' => 'required|string|unique:book_entries,book_number',
            'book_title' => 'required|string|max:255',
            'book_type' => 'required|in:incoming,outgoing,internal,circular,decision',
            'date_written' => 'required|date',
            'description' => 'nullable|string',
            'writer_name' => 'required|string|max:255',
            'writer_rank' => 'nullable|string|max:255',
            'writer_office' => 'nullable|string|max:255',
        ]);
        
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';
        
        BookEntry::create($validated);
        
        return redirect()->route('books.index')
            ->with('success', 'تم إنشاء القيد بنجاح');
    }

    public function show(BookEntry $book)
    {
        if (!$this->authorizeOwnerOrManager($book)) {
            return redirect()->route('books.index')
                ->with('error', 'غير مصرح لك بعرض هذا القيد');
        }

        $book->load(['creator', 'approver', 'signatures.user']);
        return view('books.show', compact('book'));
    }

    public function edit(BookEntry $book)
    {
        if (!$this->authorizeOwner($book)) {
            return redirect()->route('books.index')
                ->with('error', 'غير مصرح لك بتعديل هذا القيد');
        }

        if (!in_array($book->status, ['draft', 'needs_modification'])) {
            return redirect()->route('books.show', $book)
                ->with('error', 'لا يمكن تعديل هذا القيد');
        }
        
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, BookEntry $book)
    {
        if (!$this->authorizeOwner($book)) {
            return redirect()->route('books.index')
                ->with('error', 'غير مصرح لك بتعديل هذا القيد');
        }

        if (!in_array($book->status, ['draft', 'needs_modification'])) {
            return redirect()->route('books.show', $book)
                ->with('error', 'لا يمكن تعديل هذا القيد');
        }
        
        $validated = $request->validate([
            'book_title' => 'required|string|max:255',
            'book_type' => 'required|in:incoming,outgoing,internal,circular,decision',
            'date_written' => 'required|date',
            'description' => 'nullable|string',
            'writer_name' => 'required|string|max:255',
            'writer_rank' => 'nullable|string|max:255',
            'writer_office' => 'nullable|string|max:255',
        ]);
        
        if ($book->status === 'needs_modification') {
            $validated['status'] = 'draft';
        }
        
        $book->update($validated);
        
        return redirect()->route('books.show', $book)
            ->with('success', 'تم تحديث القيد بنجاح');
    }

    public function destroy(BookEntry $book)
    {
        if (!$this->authorizeOwner($book)) {
            return redirect()->route('books.index')
                ->with('error', 'غير مصرح لك بحذف هذا القيد');
        }

        if ($book->status !== 'draft') {
            return redirect()->route('books.index')
                ->with('error', 'لا يمكن حذف هذا القيد');
        }
        
        $book->delete();
        
        return redirect()->route('books.index')
            ->with('success', 'تم حذف القيد بنجاح');
    }

    public function sendToManager(BookEntry $book)
    {
        if (!$this->authorizeOwner($book)) {
            return redirect()->route('books.index')
                ->with('error', 'غير مصرح لك بإرسال هذا القيد');
        }

        if ($book->status !== 'draft') {
            return redirect()->route('books.show', $book)
                ->with('error', 'لا يمكن إرسال هذا القيد');
        }
        
        $book->update(['status' => 'submitted']);
        
        Notification::createBookNotification($book, 'submitted', Auth::user());
        
        return redirect()->route('books.show', $book)
            ->with('success', 'تم إرسال القيد للمدير للاعتماد');
    }

    public function inbox()
    {
        $entries = BookEntry::with(['creator'])
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('books.inbox', compact('entries'));
    }

    public function approve(Request $request, BookEntry $book)
    {
        if ($book->status !== 'submitted') {
            return redirect()->route('books.show', $book)
                ->with('error', 'لا يمكن اعتماد هذا القيد');
        }
        
        $request->validate([
            'signature' => 'required|string',
            'comments' => 'nullable|string',
        ]);
        
        Signature::create([
            'user_id' => Auth::id(),
            'signable_type' => BookEntry::class,
            'signable_id' => $book->id,
            'signature_data' => $request->signature,
            'signature_hash' => Signature::generateHash($request->signature, Auth::id(), BookEntry::class, $book->id),
            'action' => 'approved',
            'comments' => $request->comments,
            'ip_address' => $request->ip(),
        ]);
        
        $book->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'manager_comment' => $request->comments,
        ]);
        
        Notification::createBookNotification($book, 'approved', Auth::user());
        
        return redirect()->route('books.show', $book)
            ->with('success', 'تم اعتماد القيد بنجاح');
    }

    public function reject(Request $request, BookEntry $book)
    {
        if ($book->status !== 'submitted') {
            return redirect()->route('books.show', $book)
                ->with('error', 'لا يمكن رفض هذا القيد');
        }
        
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);
        
        Signature::create([
            'user_id' => Auth::id(),
            'signable_type' => BookEntry::class,
            'signable_id' => $book->id,
            'signature_data' => '',
            'signature_hash' => Signature::generateHash('rejected', Auth::id(), BookEntry::class, $book->id),
            'action' => 'rejected',
            'comments' => $request->reason,
            'ip_address' => $request->ip(),
        ]);
        
        $book->update([
            'status' => 'rejected',
            'manager_comment' => $request->reason,
        ]);
        
        Notification::createBookNotification($book, 'rejected', Auth::user());
        
        return redirect()->route('books.show', $book)
            ->with('success', 'تم رفض القيد');
    }

    public function requestEdit(Request $request, BookEntry $book)
    {
        if ($book->status !== 'submitted') {
            return redirect()->route('books.show', $book)
                ->with('error', 'لا يمكن طلب تعديل هذا القيد');
        }
        
        $request->validate([
            'reason' => 'required|string|min:10',
        ]);
        
        $book->update([
            'status' => 'needs_modification',
            'manager_comment' => $request->reason,
        ]);
        
        Notification::createBookNotification($book, 'needs_modification', Auth::user());
        
        return redirect()->route('books.show', $book)
            ->with('success', 'تم إرجاع القيد للتعديل');
    }

    public function print(BookEntry $book)
    {
        if (!$this->authorizeOwnerOrManager($book)) {
            return redirect()->route('books.index')
                ->with('error', 'غير مصرح لك بطباعة هذا القيد');
        }

        $book->load(['creator', 'approver', 'signatures.user']);
        return view('books.print', compact('book'));
    }

    public function myBooks()
    {
        $entries = BookEntry::with(['creator', 'approver'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('books.my-books', compact('entries'));
    }
}
