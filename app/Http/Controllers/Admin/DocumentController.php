<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\Department;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['department', 'creator', 'assignedUser']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.documents.index', compact('documents', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.documents.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:letter,memo,report,decision,circular,other',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,normal,high,urgent',
            'file' => 'nullable|file|mimes:doc,docx,pdf,rtf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم إنشاء المستند بنجاح');
    }

    public function show(Document $document)
    {
        $document->load(['department', 'creator', 'assignedUser', 'approver', 'workflows.fromUser', 'workflows.toUser']);
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        if (!in_array($document->status, ['draft', 'needs_modification'])) {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'لا يمكن تعديل مستند في هذه الحالة');
        }

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.documents.edit', compact('document', 'departments'));
    }

    public function update(Request $request, Document $document)
    {
        if (!in_array($document->status, ['draft', 'needs_modification'])) {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'لا يمكن تعديل مستند في هذه الحالة');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:letter,memo,report,decision,circular,other',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,normal,high,urgent',
            'file' => 'nullable|file|mimes:doc,docx,pdf,rtf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('documents', 'public');
        }

        if ($document->status === 'needs_modification') {
            $validated['status'] = 'draft';
            $validated['modification_notes'] = null;
        }

        $document->update($validated);

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم تحديث المستند بنجاح');
    }

    public function destroy(Document $document)
    {
        if ($document->status !== 'draft') {
            return redirect()->route('admin.documents.index')
                ->with('error', 'لا يمكن حذف مستند غير مسودة');
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم حذف المستند بنجاح');
    }

    public function sendForReview(Request $request, Document $document)
    {
        if ($document->status !== 'draft') {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'المستند ليس مسودة');
        }

        $validated = $request->validate([
            'reviewer_id' => 'required|exists:users,id',
            'comments' => 'nullable|string',
        ]);

        $document->update([
            'status' => 'pending_review',
            'assigned_to' => $validated['reviewer_id'],
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $validated['reviewer_id'],
            'action' => 'submit',
            'comments' => $validated['comments'],
            'status' => 'pending',
        ]);

        Notification::createDocumentNotification($document, 'assigned', auth()->user());

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم إرسال المستند للمراجعة');
    }

    public function sendToManager(Request $request, Document $document)
    {
        if ($document->status !== 'pending_review') {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'المستند ليس قيد المراجعة');
        }

        $validated = $request->validate([
            'manager_id' => 'required|exists:users,id',
            'comments' => 'nullable|string',
        ]);

        DocumentWorkflow::where('document_id', $document->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed', 'completed_at' => now()]);

        $document->update([
            'status' => 'pending_approval',
            'assigned_to' => $validated['manager_id'],
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $validated['manager_id'],
            'action' => 'forward',
            'comments' => $validated['comments'],
            'status' => 'pending',
        ]);

        Notification::createDocumentNotification($document, 'assigned', auth()->user());

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم إرسال المستند للمدير للاعتماد');
    }

    public function approve(Request $request, Document $document)
    {
        if ($document->status !== 'pending_approval') {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'المستند ليس قيد الاعتماد');
        }

        $validated = $request->validate([
            'signature_data' => 'required|string',
            'comments' => 'nullable|string',
        ]);

        DocumentWorkflow::where('document_id', $document->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed', 'completed_at' => now()]);

        $document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'signature_data' => $validated['signature_data'],
            'assigned_to' => null,
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $document->created_by,
            'action' => 'approve',
            'comments' => $validated['comments'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        Notification::createDocumentNotification($document, 'approved', auth()->user());

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم اعتماد المستند بنجاح');
    }

    public function reject(Request $request, Document $document)
    {
        if ($document->status !== 'pending_approval') {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'المستند ليس قيد الاعتماد');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        DocumentWorkflow::where('document_id', $document->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed', 'completed_at' => now()]);

        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'assigned_to' => null,
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $document->created_by,
            'action' => 'reject',
            'comments' => $validated['rejection_reason'],
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        Notification::createDocumentNotification($document, 'rejected', auth()->user());

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم رفض المستند');
    }

    public function requestModification(Request $request, Document $document)
    {
        if (!in_array($document->status, ['pending_review', 'pending_approval'])) {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'لا يمكن طلب تعديل في هذه الحالة');
        }

        $validated = $request->validate([
            'modification_notes' => 'required|string',
        ]);

        DocumentWorkflow::where('document_id', $document->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed', 'completed_at' => now()]);

        $document->update([
            'status' => 'needs_modification',
            'modification_notes' => $validated['modification_notes'],
            'assigned_to' => $document->created_by,
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $document->created_by,
            'action' => 'request_modification',
            'comments' => $validated['modification_notes'],
            'status' => 'pending',
        ]);

        Notification::createDocumentNotification($document, 'needs_modification', auth()->user());

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم إرسال طلب التعديل');
    }

    public function inbox()
    {
        $documents = Document::with(['department', 'creator'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['pending_review', 'pending_approval', 'needs_modification'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.documents.inbox', compact('documents'));
    }

    public function myDocuments()
    {
        $documents = Document::with(['department', 'assignedUser'])
            ->where('created_by', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.documents.my-documents', compact('documents'));
    }

    public function print(Document $document)
    {
        $document->load(['department', 'creator', 'approver']);
        return view('admin.documents.print', compact('document'));
    }
}
