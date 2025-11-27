@extends('layouts.app')

@section('title', 'المرسلات والكتب')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">المرسلات والكتب</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">إدارة الكتب والمراسلات الواردة والصادرة</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.correspondences.search-form') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-white rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                بحث متقدم
            </a>
            <a href="{{ route('admin.correspondences.import') }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                استيراد ملف
            </a>
            <a href="{{ route('admin.correspondences.create') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة مراسلة
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">إجمالي المراسلات</p>
                    <p class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">الوارد</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $stats['incoming'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">الصادر</p>
                    <p class="text-2xl font-bold text-sky-600 dark:text-sky-400 mt-1">{{ $stats['outgoing'] }}</p>
                </div>
                <div class="w-12 h-12 bg-sky-100 dark:bg-sky-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 dark:text-slate-400">جديد</p>
                    <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['new'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث..." class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white text-sm focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                <select name="type" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white text-sm">
                    <option value="">جميع الأنواع</option>
                    <option value="incoming" {{ request('type') === 'incoming' ? 'selected' : '' }}>وارد</option>
                    <option value="outgoing" {{ request('type') === 'outgoing' ? 'selected' : '' }}>صادر</option>
                </select>
                <select name="status" class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white text-sm">
                    <option value="">جميع الحالات</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>جديد</option>
                    <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>مؤرشف</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition text-sm">تصفية</button>
                @if(request()->hasAny(['search', 'type', 'status']))
                <a href="{{ route('admin.correspondences.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-white rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500 transition text-sm">إعادة تعيين</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">رقم الكتاب</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">العنوان</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">النوع</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">الجهة</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">التاريخ</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">الحالة</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">الملف</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($correspondences as $correspondence)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <td class="px-6 py-4 text-sm font-medium text-slate-800 dark:text-white">{{ $correspondence->document_number }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ Str::limit($correspondence->title, 40) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $correspondence->type === 'incoming' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-400' }}">
                                {{ $correspondence->type_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                            {{ $correspondence->type === 'incoming' ? $correspondence->from_department : $correspondence->to_department }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $correspondence->document_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $correspondence->status_color }}-100 text-{{ $correspondence->status_color }}-800 dark:bg-{{ $correspondence->status_color }}-900/30 dark:text-{{ $correspondence->status_color }}-400">
                                {{ $correspondence->status_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($correspondence->file_path)
                            <div class="flex items-center gap-1">
                                @if($correspondence->isPdf())
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                @elseif($correspondence->isWord())
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M15.2,14.9L14,18h-1l-1.2-3.1L10.6,18h-1l-1.5-5h1 l0.9,3.3L11.2,13h0.6l1.2,3.3l0.9-3.3h1L15.2,14.9z M13,9V3.5L18.5,9H13z"/></svg>
                                @elseif($correspondence->isImage())
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                @else
                                <svg class="w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                @endif
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ $correspondence->formatted_file_size }}</span>
                            </div>
                            @else
                            <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($correspondence->file_path && ($correspondence->isPdf() || $correspondence->isImage()))
                                <button type="button" 
                                    onclick="openPreview('{{ asset('storage/' . $correspondence->file_path) }}', '{{ $correspondence->isPdf() ? 'pdf' : 'image' }}', '{{ $correspondence->title }}')" 
                                    class="p-1.5 text-violet-600 hover:bg-violet-50 dark:hover:bg-violet-900/30 rounded transition" 
                                    title="معاينة سريعة">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                    </svg>
                                </button>
                                @endif
                                <a href="{{ route('admin.correspondences.show', $correspondence) }}" class="p-1.5 text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-900/30 rounded transition" title="عرض">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.correspondences.edit', $correspondence) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded transition" title="تعديل">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if($correspondence->file_path)
                                <a href="{{ route('admin.correspondences.download', $correspondence) }}" class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded transition" title="تحميل">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                                @endif
                                <form action="{{ route('admin.correspondences.destroy', $correspondence) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المراسلة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded transition" title="حذف">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            لا توجد مراسلات
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($correspondences->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $correspondences->links() }}
        </div>
        @endif
    </div>
</div>

<div id="previewModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closePreview()"></div>
    <div class="absolute inset-4 md:inset-8 lg:inset-12 bg-white dark:bg-slate-800 rounded-2xl shadow-2xl flex flex-col overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
            <h3 id="previewTitle" class="text-lg font-bold text-slate-800 dark:text-white">معاينة الملف</h3>
            <div class="flex items-center gap-2">
                <a id="previewDownload" href="#" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    تحميل
                </a>
                <button onclick="closePreview()" class="p-2 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div id="previewContent" class="flex-1 overflow-auto p-4 bg-slate-100 dark:bg-slate-900">
        </div>
    </div>
</div>

@push('scripts')
<script>
function openPreview(url, type, title) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    const titleEl = document.getElementById('previewTitle');
    const downloadBtn = document.getElementById('previewDownload');
    
    titleEl.textContent = title || 'معاينة الملف';
    downloadBtn.href = url;
    downloadBtn.setAttribute('download', '');
    
    if (type === 'pdf') {
        content.innerHTML = `
            <iframe src="${url}#toolbar=1&navpanes=0" class="w-full h-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white" style="min-height: 100%;"></iframe>
        `;
    } else if (type === 'image') {
        content.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <img src="${url}" alt="${title}" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
            </div>
        `;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePreview() {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    
    modal.classList.add('hidden');
    content.innerHTML = '';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreview();
    }
});
</script>
@endpush
@endsection
