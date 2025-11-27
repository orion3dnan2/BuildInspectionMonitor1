@extends('layouts.app')

@section('title', 'البحث في المرسلات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">البحث في المرسلات</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">ابحث عن الكتب والمراسلات باستخدام معايير متعددة</p>
        </div>
        <a href="{{ route('admin.correspondences.index') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-white rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            العودة للقائمة
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">معايير البحث</h2>
        </div>
        <form method="GET" action="{{ route('admin.correspondences.search') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">رقم الكتاب</label>
                    <input type="text" name="document_number" value="{{ request('document_number') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" placeholder="أدخل رقم الكتاب">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">العنوان</label>
                    <input type="text" name="title" value="{{ request('title') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" placeholder="أدخل العنوان">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">نوع المراسلة</label>
                    <select name="type" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">جميع الأنواع</option>
                        <option value="incoming" {{ request('type') === 'incoming' ? 'selected' : '' }}>وارد</option>
                        <option value="outgoing" {{ request('type') === 'outgoing' ? 'selected' : '' }}>صادر</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الجهة</label>
                    <input type="text" name="department" value="{{ request('department') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" placeholder="الجهة المرسلة أو المستلمة">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">من تاريخ</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">إلى تاريخ</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الحالة</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>جديد</option>
                        <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>مؤرشف</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">نوع الملف</label>
                    <select name="file_type" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                        <option value="">جميع الأنواع</option>
                        <option value="pdf" {{ request('file_type') === 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="word" {{ request('file_type') === 'word' ? 'selected' : '' }}>Word</option>
                        <option value="image" {{ request('file_type') === 'image' ? 'selected' : '' }}>صور</option>
                        <option value="other" {{ request('file_type') === 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.correspondences.search-form') }}" class="px-6 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition">إعادة تعيين</a>
                <button type="submit" class="px-6 py-2.5 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    بحث
                </button>
            </div>
        </form>
    </div>

    @if($searched)
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">نتائج البحث</h2>
            <span class="text-sm text-slate-500 dark:text-slate-400">{{ $correspondences->total() }} نتيجة</span>
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
                            <div class="flex items-center gap-2">
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
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            لا توجد نتائج مطابقة لمعايير البحث
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
    @endif
</div>
@endsection
