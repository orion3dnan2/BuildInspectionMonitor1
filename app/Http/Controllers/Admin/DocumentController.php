<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\Department;
use App\Models\Notification;
use App\Models\User;
use App\Services\PdfConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    protected PdfConversionService $pdfService;

    public function __construct(PdfConversionService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

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
            'content' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,normal,high,urgent',
            'file' => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        if ($request->hasFile('file')) {
            $result = $this->pdfService->uploadAndConvert($request->file('file'));
            
            if ($result['success']) {
                $validated['original_file_path'] = $result['original_file_path'];
                $validated['pdf_path'] = $result['pdf_path'];
                $validated['file_path'] = $result['pdf_path'];
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message']);
            }
        }

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم إنشاء المستند بنجاح');
    }

    public function show(Document $document)
    {
        $document->load(['department', 'creator', 'assignedUser', 'approver', 'signer', 'workflows.fromUser', 'workflows.toUser']);
        $users = User::orderBy('name')->get();
        return view('admin.documents.show', compact('document', 'users'));
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
            'content' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,normal,high,urgent',
            'file' => 'nullable|file|mimes:doc,docx,pdf|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($document->original_file_path) {
                Storage::delete($document->original_file_path);
            }
            if ($document->pdf_path && $document->pdf_path !== $document->original_file_path) {
                Storage::delete($document->pdf_path);
            }

            $result = $this->pdfService->uploadAndConvert($request->file('file'));
            
            if ($result['success']) {
                $validated['original_file_path'] = $result['original_file_path'];
                $validated['pdf_path'] = $result['pdf_path'];
                $validated['file_path'] = $result['pdf_path'];
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message']);
            }
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

        if ($document->original_file_path) {
            Storage::delete($document->original_file_path);
        }
        if ($document->pdf_path && $document->pdf_path !== $document->original_file_path) {
            Storage::delete($document->pdf_path);
        }
        if ($document->signed_pdf_path) {
            Storage::delete($document->signed_pdf_path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم حذف المستند بنجاح');
    }

    public function viewPdf(Document $document)
    {
        $pdfPath = $document->getViewablePdfPath();
        
        if (!$pdfPath || !Storage::exists($pdfPath)) {
            abort(404, 'PDF not found');
        }

        $content = Storage::get($pdfPath);
        
        return Response::make($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document->document_number . '.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    public function downloadPdf(Document $document)
    {
        $pdfPath = $document->getViewablePdfPath();
        
        if (!$pdfPath || !Storage::exists($pdfPath)) {
            abort(404, 'PDF not found');
        }

        return Storage::download($pdfPath, $document->document_number . '.pdf');
    }

    public function sign(Request $request, Document $document)
    {
        if ($document->is_signed) {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'المستند موقّع مسبقاً');
        }

        $pdfPath = $document->getViewablePdfPath();
        if (!$pdfPath) {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'لا يوجد ملف PDF للتوقيع');
        }

        $validated = $request->validate([
            'signature_data' => 'required|string',
            'signature_x' => 'nullable|numeric',
            'signature_y' => 'nullable|numeric',
            'signature_page' => 'nullable|integer|min:1',
        ]);

        $options = [
            'x' => $validated['signature_x'] ?? 120,
            'y' => $validated['signature_y'] ?? 250,
            'page' => $validated['signature_page'] ?? null,
            'width' => 50,
        ];

        $signedPdfPath = $this->pdfService->addSignatureToPdf($pdfPath, $validated['signature_data'], $options);

        if (!$signedPdfPath) {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'فشل في إضافة التوقيع إلى المستند');
        }

        $document->update([
            'signed_pdf_path' => $signedPdfPath,
            'signature_data' => $validated['signature_data'],
            'is_signed' => true,
            'signed_at' => now(),
            'signed_by' => auth()->id(),
            'status' => 'signed',
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $document->created_by,
            'action' => 'sign',
            'comments' => 'تم توقيع المستند',
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم توقيع المستند بنجاح');
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
            'status' => 'under_review',
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
        if (!in_array($document->status, ['under_review', 'pending_review', 'signed'])) {
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
            'signature_data' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        DocumentWorkflow::where('document_id', $document->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed', 'completed_at' => now()]);

        $updateData = [
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'assigned_to' => null,
        ];

        if (!empty($validated['signature_data']) && !$document->is_signed) {
            $pdfPath = $document->getViewablePdfPath();
            if ($pdfPath) {
                $signedPdfPath = $this->pdfService->addSignatureToPdf($pdfPath, $validated['signature_data']);
                if ($signedPdfPath) {
                    $updateData['signed_pdf_path'] = $signedPdfPath;
                    $updateData['signature_data'] = $validated['signature_data'];
                    $updateData['is_signed'] = true;
                    $updateData['signed_at'] = now();
                    $updateData['signed_by'] = auth()->id();
                }
            }
        }

        $document->update($updateData);

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
        if (!in_array($document->status, ['under_review', 'pending_review', 'pending_approval'])) {
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

    public function archive(Document $document)
    {
        if ($document->status !== 'approved') {
            return redirect()->route('admin.documents.show', $document)
                ->with('error', 'يجب أن يكون المستند معتمداً للأرشفة');
        }

        $document->update([
            'status' => 'archived',
            'archived_at' => now(),
        ]);

        DocumentWorkflow::create([
            'document_id' => $document->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $document->created_by,
            'action' => 'archive',
            'comments' => 'تم أرشفة المستند',
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'تم أرشفة المستند بنجاح');
    }

    public function inbox()
    {
        $documents = Document::with(['department', 'creator'])
            ->where('assigned_to', auth()->id())
            ->whereIn('status', ['under_review', 'pending_review', 'pending_approval', 'needs_modification'])
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
        $document->load(['department', 'creator', 'approver', 'signer']);
        return view('admin.documents.print', compact('document'));
    }
}
