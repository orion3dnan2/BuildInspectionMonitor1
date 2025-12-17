@extends('layouts.app')

@section('title', 'عرض المراسلة - ' . $correspondence->document_number)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.correspondences.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white text-sm mb-4 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للقائمة
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $correspondence->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">رقم الكتاب: {{ $correspondence->document_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.correspondences.viewer', $correspondence) }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    عرض وتوقيع
                </a>
                <a href="{{ route('admin.correspondences.edit', $correspondence) }}" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل
                </a>
                @if($correspondence->file_path)
                <a href="{{ route('admin.correspondences.download', $correspondence) }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    تحميل الملف
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">تفاصيل المراسلة</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">نوع المراسلة</p>
                            <span class="inline-flex items-center mt-1 px-3 py-1 rounded-full text-sm font-medium {{ $correspondence->type === 'incoming' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-400' }}">
                                {{ $correspondence->type_name }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">الحالة</p>
                            <span class="inline-flex items-center mt-1 px-3 py-1 rounded-full text-sm font-medium bg-{{ $correspondence->status_color }}-100 text-{{ $correspondence->status_color }}-800 dark:bg-{{ $correspondence->status_color }}-900/30 dark:text-{{ $correspondence->status_color }}-400">
                                {{ $correspondence->status_name }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">التاريخ</p>
                            <p class="text-slate-800 dark:text-white font-medium">{{ $correspondence->document_date->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $correspondence->type === 'incoming' ? 'الجهة المرسلة' : 'الجهة المستلمة' }}</p>
                            <p class="text-slate-800 dark:text-white font-medium">{{ $correspondence->type === 'incoming' ? ($correspondence->from_department ?: '-') : ($correspondence->to_department ?: '-') }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">الموضوع</p>
                        <p class="text-slate-800 dark:text-white font-medium">{{ $correspondence->subject }}</p>
                    </div>

                    @if($correspondence->description)
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">الوصف</p>
                        <p class="text-slate-700 dark:text-slate-300">{{ $correspondence->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($correspondence->file_path)
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">معاينة الملف</h2>
                    @if($correspondence->isWord())
                    <a href="ms-word:ofe|u|{{ url('storage/' . $correspondence->file_path) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center gap-2 text-sm" id="openInWord">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M15.2,14.9L14,18h-1l-1.2-3.1L10.6,18h-1l-1.5-5h1 l0.9,3.3L11.2,13h0.6l1.2,3.3l0.9-3.3h1L15.2,14.9z M13,9V3.5L18.5,9H13z"/></svg>
                        فتح في Word
                    </a>
                    @endif
                </div>
                <div class="p-4">
                    @if($correspondence->isPdf())
                    <iframe src="{{ asset('storage/' . $correspondence->file_path) }}" class="w-full h-[600px] rounded-lg border border-slate-200 dark:border-slate-700"></iframe>
                    @elseif($correspondence->isImage())
                    <img src="{{ asset('storage/' . $correspondence->file_path) }}" alt="{{ $correspondence->title }}" class="max-w-full h-auto rounded-lg mx-auto">
                    @elseif($correspondence->isWord())
                    <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-blue-500 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M15.2,14.9L14,18h-1l-1.2-3.1L10.6,18h-1l-1.5-5h1 l0.9,3.3L11.2,13h0.6l1.2,3.3l0.9-3.3h1L15.2,14.9z M13,9V3.5L18.5,9H13z"/></svg>
                        <p class="text-slate-700 dark:text-white font-medium mb-2">ملف Microsoft Word</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $correspondence->file_name }}</p>
                        <div class="flex items-center justify-center gap-3">
                            <a href="ms-word:ofe|u|{{ url('storage/' . $correspondence->file_path) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z"/></svg>
                                فتح في Word
                            </a>
                            <a href="{{ route('admin.correspondences.download', $correspondence) }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-white rounded-lg hover:bg-slate-300 dark:hover:bg-slate-500 transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                تحميل الملف
                            </a>
                        </div>
                        <p class="text-xs text-slate-400 mt-4">يجب أن يكون برنامج Microsoft Word مثبتاً على جهازك</p>
                    </div>
                    @else
                    <div class="text-center py-12 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-slate-700 dark:text-white font-medium mb-2">ملف مرفق</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">{{ $correspondence->file_name }}</p>
                        <a href="{{ route('admin.correspondences.download', $correspondence) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            تحميل الملف
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">معلومات الملف</h2>
                </div>
                <div class="p-4 space-y-3">
                    @if($correspondence->file_path)
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">اسم الملف</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ Str::limit($correspondence->file_name, 25) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">الحجم</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->formatted_file_size }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">النوع</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">
                            @if($correspondence->isPdf())
                            PDF
                            @elseif($correspondence->isWord())
                            Word
                            @elseif($correspondence->isImage())
                            صورة
                            @else
                            أخرى
                            @endif
                        </span>
                    </div>
                    @else
                    <p class="text-center text-slate-500 dark:text-slate-400 py-4">لا يوجد ملف مرفق</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">معلومات إضافية</h2>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">أنشأ بواسطة</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->creator->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">تاريخ الإنشاء</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($correspondence->updated_by)
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">آخر تعديل بواسطة</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->updater->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">تاريخ التعديل</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const openInWordLinks = document.querySelectorAll('[href^="ms-word:"]');
    openInWordLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            setTimeout(() => {
                if (!document.hidden) {
                    alert('يجب أن يكون برنامج Microsoft Word مثبتاً على جهازك لفتح هذا الملف.\n\nيمكنك تحميل الملف بدلاً من ذلك.');
                }
            }, 1000);
        });
    });
});
</script>
@endpush
@endsection
