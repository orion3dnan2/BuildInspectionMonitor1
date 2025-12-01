@extends('layouts.app')

@section('title', 'إضافة مراسلة جديدة')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.correspondences.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white text-sm mb-4 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للقائمة
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">إضافة مراسلة جديدة</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">أدخل بيانات الكتاب أو المراسلة الجديدة</p>
    </div>

    <form action="{{ route('admin.correspondences.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        @csrf
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">نوع المراسلة <span class="text-red-500">*</span></label>
                    <select name="type" id="correspondenceType" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                        <option value="incoming" {{ old('type') === 'incoming' ? 'selected' : '' }}>وارد</option>
                        <option value="outgoing" {{ old('type') === 'outgoing' ? 'selected' : '' }}>صادر</option>
                    </select>
                    @error('type')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">رقم الكتاب <span class="text-red-500">*</span></label>
                    <input type="text" name="document_number" id="documentNumber" value="{{ old('document_number', $nextIncoming) }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                    @error('document_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">عنوان الكتاب <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                @error('title')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div id="fromDepartmentField">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الجهة المرسلة</label>
                    <input type="text" name="from_department" value="{{ old('from_department') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    @error('from_department')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div id="toDepartmentField">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الجهة المستلمة</label>
                    <input type="text" name="to_department" value="{{ old('to_department') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                    @error('to_department')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">تاريخ المراسلة <span class="text-red-500">*</span></label>
                    <input type="date" name="document_date" value="{{ old('document_date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                    @error('document_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                        <option value="new" {{ old('status') === 'new' ? 'selected' : '' }}>جديد</option>
                        <option value="reviewed" {{ old('status') === 'reviewed' ? 'selected' : '' }}>تمت المراجعة</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>مؤرشف</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الموضوع <span class="text-red-500">*</span></label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                @error('subject')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">وصف مختصر</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">ملف الكتاب</label>
                <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center">
                    <input type="file" name="file" id="fileInput" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.gif,.xls,.xlsx,.txt">
                    <label for="fileInput" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">اسحب الملف هنا أو <span class="text-sky-500">اختر ملف</span></p>
                        <p class="mt-1 text-xs text-slate-400">PDF, Word, صور (حتى 20 ميجابايت)</p>
                    </label>
                    <div id="filePreview" class="hidden mt-4 p-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-8 h-8 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                                <div>
                                    <p id="fileName" class="text-sm font-medium text-slate-700 dark:text-white"></p>
                                    <p id="fileSize" class="text-xs text-slate-500"></p>
                                </div>
                            </div>
                            <button type="button" id="removeFile" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @error('file')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.correspondences.index') }}" class="px-6 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">حفظ المراسلة</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('correspondenceType');
    const documentNumber = document.getElementById('documentNumber');
    const nextIncoming = '{{ $nextIncoming }}';
    const nextOutgoing = '{{ $nextOutgoing }}';

    typeSelect.addEventListener('change', function() {
        documentNumber.value = this.value === 'incoming' ? nextIncoming : nextOutgoing;
    });

    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFile = document.getElementById('removeFile');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            const file = this.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.classList.remove('hidden');
        }
    });

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
    });

    function formatFileSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        let i = 0;
        while (bytes > 1024 && i < units.length - 1) {
            bytes /= 1024;
            i++;
        }
        return bytes.toFixed(2) + ' ' + units[i];
    }
});
</script>
@endpush
@endsection
