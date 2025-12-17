<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use App\Models\Signature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CorrespondenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Correspondence::with('creator')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('document_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('document_date', '<=', $request->to_date);
        }

        if ($request->filled('file_type')) {
            $fileType = $request->file_type;
            $query->where(function ($q) use ($fileType) {
                if ($fileType === 'pdf') {
                    $q->where('file_type', 'like', '%pdf%');
                } elseif ($fileType === 'word') {
                    $q->where('file_type', 'like', '%word%')
                      ->orWhere('file_type', 'like', '%document%');
                } elseif ($fileType === 'image') {
                    $q->where('file_type', 'like', '%image%');
                }
            });
        }

        $correspondences = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Correspondence::count(),
            'incoming' => Correspondence::incoming()->count(),
            'outgoing' => Correspondence::outgoing()->count(),
            'new' => Correspondence::status('new')->count(),
        ];

        return view('admin.correspondences.index', compact('correspondences', 'stats'));
    }

    public function create()
    {
        $nextIncoming = Correspondence::generateDocumentNumber('incoming');
        $nextOutgoing = Correspondence::generateDocumentNumber('outgoing');
        
        return view('admin.correspondences.create', compact('nextIncoming', 'nextOutgoing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:50|unique:correspondences,document_number',
            'title' => 'required|string|max:255',
            'type' => 'required|in:incoming,outgoing',
            'from_department' => 'nullable|string|max:255',
            'to_department' => 'nullable|string|max:255',
            'document_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:new,reviewed,completed,archived',
            'file' => 'nullable|file|max:20480',
        ]);

        $data = [
            'document_number' => $validated['document_number'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'from_department' => $validated['from_department'],
            'to_department' => $validated['to_department'],
            'document_date' => $validated['document_date'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('correspondences', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();
        }

        Correspondence::create($data);

        return redirect()->route('admin.correspondences.index')
            ->with('success', 'تم إضافة المراسلة بنجاح');
    }

    public function show(Correspondence $correspondence)
    {
        return view('admin.correspondences.show', compact('correspondence'));
    }

    public function edit(Correspondence $correspondence)
    {
        return view('admin.correspondences.edit', compact('correspondence'));
    }

    public function update(Request $request, Correspondence $correspondence)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:50|unique:correspondences,document_number,' . $correspondence->id,
            'title' => 'required|string|max:255',
            'type' => 'required|in:incoming,outgoing',
            'from_department' => 'nullable|string|max:255',
            'to_department' => 'nullable|string|max:255',
            'document_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:new,reviewed,completed,archived',
            'file' => 'nullable|file|max:20480',
        ]);

        $data = [
            'document_number' => $validated['document_number'],
            'title' => $validated['title'],
            'type' => $validated['type'],
            'from_department' => $validated['from_department'],
            'to_department' => $validated['to_department'],
            'document_date' => $validated['document_date'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'updated_by' => Auth::id(),
        ];

        if ($request->hasFile('file')) {
            if ($correspondence->file_path) {
                Storage::disk('public')->delete($correspondence->file_path);
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('correspondences', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();
        }

        $correspondence->update($data);

        return redirect()->route('admin.correspondences.index')
            ->with('success', 'تم تحديث المراسلة بنجاح');
    }

    public function destroy(Correspondence $correspondence)
    {
        if ($correspondence->file_path) {
            Storage::disk('public')->delete($correspondence->file_path);
        }
        
        $correspondence->delete();

        return redirect()->route('admin.correspondences.index')
            ->with('success', 'تم حذف المراسلة بنجاح');
    }

    public function import()
    {
        return view('admin.correspondences.import');
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'type' => 'required|in:incoming,outgoing',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('correspondences', $fileName, 'public');

        $correspondence = Correspondence::create([
            'document_number' => Correspondence::generateDocumentNumber($request->type),
            'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'type' => $request->type,
            'document_date' => now(),
            'subject' => 'مستورد: ' . $file->getClientOriginalName(),
            'status' => 'new',
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.correspondences.show', $correspondence)
            ->with('success', 'تم استيراد الملف بنجاح');
    }

    public function download(Correspondence $correspondence)
    {
        if (!$correspondence->file_path || !Storage::disk('public')->exists($correspondence->file_path)) {
            return back()->with('error', 'الملف غير موجود');
        }

        return Storage::disk('public')->download($correspondence->file_path, $correspondence->file_name);
    }

    public function search(Request $request)
    {
        $query = Correspondence::with('creator')
            ->orderBy('created_at', 'desc');

        if ($request->filled('document_number')) {
            $query->where('document_number', 'like', '%' . $request->document_number . '%');
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('department')) {
            $query->where(function ($q) use ($request) {
                $q->where('from_department', 'like', '%' . $request->department . '%')
                  ->orWhere('to_department', 'like', '%' . $request->department . '%');
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('document_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('document_date', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('file_type')) {
            $fileType = $request->file_type;
            $query->where(function ($q) use ($fileType) {
                if ($fileType === 'pdf') {
                    $q->where('file_type', 'like', '%pdf%');
                } elseif ($fileType === 'word') {
                    $q->where('file_type', 'like', '%word%')
                      ->orWhere('file_type', 'like', '%document%');
                } elseif ($fileType === 'image') {
                    $q->where('file_type', 'like', '%image%');
                } else {
                    $q->whereNotNull('file_type')
                      ->where('file_type', 'not like', '%pdf%')
                      ->where('file_type', 'not like', '%word%')
                      ->where('file_type', 'not like', '%document%')
                      ->where('file_type', 'not like', '%image%');
                }
            });
        }

        $correspondences = $query->paginate(15)->withQueryString();
        $searched = true;

        return view('admin.correspondences.search', compact('correspondences', 'searched'));
    }

    public function searchForm()
    {
        return view('admin.correspondences.search', ['searched' => false, 'correspondences' => collect()]);
    }

    public function viewer(Correspondence $correspondence)
    {
        $correspondence->load(['signatures.user', 'creator', 'updater']);
        return view('admin.correspondences.viewer', compact('correspondence'));
    }

    public function sign(Request $request, Correspondence $correspondence)
    {
        $validated = $request->validate([
            'signature_data' => 'required|string',
            'action' => 'required|in:approved,rejected,reviewed',
            'comments' => 'nullable|string|max:500',
        ]);

        $signature = Signature::create([
            'user_id' => Auth::id(),
            'signable_type' => Correspondence::class,
            'signable_id' => $correspondence->id,
            'signature_data' => $validated['signature_data'],
            'signature_hash' => Signature::generateHash(
                $validated['signature_data'],
                Auth::id(),
                Correspondence::class,
                $correspondence->id
            ),
            'action' => $validated['action'],
            'comments' => $validated['comments'],
            'ip_address' => $request->ip(),
        ]);

        if ($validated['action'] === 'approved') {
            $correspondence->update(['status' => 'completed']);
        } elseif ($validated['action'] === 'reviewed') {
            $correspondence->update(['status' => 'reviewed']);
        }

        return redirect()->route('admin.correspondences.viewer', $correspondence)
            ->with('success', 'تم إضافة التوقيع بنجاح');
    }
}
