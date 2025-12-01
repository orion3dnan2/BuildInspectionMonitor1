@extends('layouts.app')

@section('title', 'مستنداتي')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">مستنداتي</h1>
            <p class="text-slate-500 mt-1">المستندات التي قمت بإنشائها</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                جميع المستندات
            </a>
            <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                مستند جديد
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">رقم المستند</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">العنوان</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">النوع</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">المحال إليه</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">التاريخ</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">الإجراء</th>
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
                <td class="px-6 py-4 text-slate-600">{{ $document->assignedUser?->name ?? '-' }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $document->created_at->format('Y/m/d') }}</td>
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.documents.show', $document) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm rounded-lg transition">
                        عرض
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                    لم تقم بإنشاء أي مستندات بعد
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
