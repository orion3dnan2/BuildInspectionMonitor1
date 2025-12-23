@extends('layouts.app')

@section('title', 'إدارة المستندات')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">إدارة المستندات والمراسلات</h1>
            <p class="text-slate-500 mt-1">كتابة وإدارة الكتب والمذكرات الرسمية</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documents.inbox') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                صندوق الوارد
            </a>
            <a href="{{ route('admin.documents.my-documents') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                مستنداتي
            </a>
            <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إنشاء مستند جديد
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-100">
        <form action="{{ route('admin.documents.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث برقم المستند أو العنوان..." class="flex-1 min-w-[200px] px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <select name="type" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الأنواع</option>
                <option value="letter" {{ request('type') == 'letter' ? 'selected' : '' }}>كتاب</option>
                <option value="memo" {{ request('type') == 'memo' ? 'selected' : '' }}>مذكرة</option>
                <option value="report" {{ request('type') == 'report' ? 'selected' : '' }}>تقرير</option>
                <option value="decision" {{ request('type') == 'decision' ? 'selected' : '' }}>قرار</option>
                <option value="circular" {{ request('type') == 'circular' ? 'selected' : '' }}>تعميم</option>
            </select>
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الحالات</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>قيد الاعتماد</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                <option value="needs_modification" {{ request('status') == 'needs_modification' ? 'selected' : '' }}>يحتاج تعديل</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                بحث
            </button>
        </form>
    </div>

    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">رقم المستند</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">العنوان</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">النوع</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">المنشئ</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">التاريخ</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">الإجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($documents as $document)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-slate-600 font-mono text-sm">{{ $document->document_number }}</td>
                <td class="px-6 py-4">
                    <span class="font-medium text-slate-800">{{ Str::limit($document->title, 40) }}</span>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $document->type_label }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $document->creator?->name ?? '-' }}</td>
                <td class="px-6 py-4">
                    @switch($document->status)
                        @case('draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">مسودة</span>
                            @break
                        @case('pending_review')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">قيد المراجعة</span>
                            @break
                        @case('pending_approval')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">قيد الاعتماد</span>
                            @break
                        @case('approved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">معتمد</span>
                            @break
                        @case('rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">مرفوض</span>
                            @break
                        @case('needs_modification')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">يحتاج تعديل</span>
                            @break
                    @endswitch
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $document->created_at->format('Y/m/d') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.documents.show', $document) }}" class="p-2 text-slate-400 hover:text-sky-600 transition" title="عرض">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        @if(in_array($document->status, ['draft', 'needs_modification']))
                        <a href="{{ route('admin.documents.edit', $document) }}" class="p-2 text-slate-400 hover:text-amber-600 transition" title="تعديل">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        @endif
                        @if(in_array($document->status, ['draft', 'approved', 'signed']))
                        <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستند؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition" title="حذف">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                    لا توجد مستندات
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($documents->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $documents->links() }}
    </div>
    @endif
</div>
@endsection
