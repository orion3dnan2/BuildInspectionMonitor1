@extends('layouts.app')

@section('title', 'استيراد كتاب من الجهاز')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.correspondences.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white text-sm mb-4 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للقائمة
        </a>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">استيراد كتاب من الجهاز</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">ارفع ملف من جهازك لإنشاء مراسلة جديدة</p>
    </div>

    <form action="{{ route('admin.correspondences.store-import') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        @csrf
        
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">نوع المراسلة <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20">
                        <input type="radio" name="type" value="incoming" class="sr-only peer" checked>
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto text-emerald-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <span class="font-medium text-emerald-700 dark:text-emerald-400">وارد</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-emerald-500 rounded-xl pointer-events-none"></div>
                    </label>
                    <label class="relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all border-slate-200 dark:border-slate-600 hover:border-sky-300 dark:hover:border-sky-700">
                        <input type="radio" name="type" value="outgoing" class="sr-only peer">
                        <div class="text-center">
                            <svg class="w-8 h-8 mx-auto text-sky-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            <span class="font-medium text-slate-700 dark:text-slate-300 peer-checked:text-sky-600">صادر</span>
                        </div>
                        <div class="absolute inset-0 border-2 border-transparent peer-checked:border-sky-500 rounded-xl pointer-events-none"></div>
                    </label>
                </div>
                @error('type')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">اختر الملف <span class="text-red-500">*</span></label>
                <div id="dropZone" class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center transition-all hover:border-sky-400 dark:hover:border-sky-600">
                    <input type="file" name="file" id="fileInput" class="hidden" required>
                    <label for="fileInput" class="cursor-pointer">
                        <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-lg text-slate-600 dark:text-slate-400 mb-2">اسحب الملف هنا أو <span class="text-sky-500 font-medium">اختر ملف</span></p>
                        <p class="text-sm text-slate-400">الصيغ المدعومة:</p>
                        <div class="flex items-center justify-center gap-4 mt-3">
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
                                <span class="text-xs text-slate-500">PDF</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z"/></svg>
                                <span class="text-xs text-slate-500">Word</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2z"/></svg>
                                <span class="text-xs text-slate-500">صور</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z"/></svg>
                                <span class="text-xs text-slate-500">أخرى</span>
                            </div>
                        </div>
                    </label>
                </div>
                <div id="filePreview" class="hidden mt-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div id="fileIcon" class="w-12 h-12 rounded-lg flex items-center justify-center bg-slate-200 dark:bg-slate-600">
                                <svg class="w-6 h-6 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/></svg>
                            </div>
                            <div>
                                <p id="fileName" class="font-medium text-slate-700 dark:text-white"></p>
                                <p id="fileSize" class="text-sm text-slate-500"></p>
                            </div>
                        </div>
                        <button type="button" id="removeFile" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @error('file')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-amber-800 dark:text-amber-300">ملاحظات هامة:</p>
                        <ul class="mt-2 text-sm text-amber-700 dark:text-amber-400 space-y-1">
                            <li>• ملفات Word تحتاج لبرنامج Microsoft Word للتعديل</li>
                            <li>• ملفات PDF يمكن معاينتها مباشرة في النظام</li>
                            <li>• الحد الأقصى لحجم الملف: 20 ميجابايت</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end gap-3 rounded-b-xl">
            <a href="{{ route('admin.correspondences.index') }}" class="px-6 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-white rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                استيراد الملف
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const fileIcon = document.getElementById('fileIcon');
    const removeFile = document.getElementById('removeFile');
    const typeRadios = document.querySelectorAll('input[name="type"]');

    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            typeRadios.forEach(r => {
                const label = r.closest('label');
                if (r.checked) {
                    if (r.value === 'incoming') {
                        label.className = 'relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20';
                    } else {
                        label.className = 'relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all border-sky-500 bg-sky-50 dark:bg-sky-900/20';
                    }
                } else {
                    label.className = 'relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all border-slate-200 dark:border-slate-600 hover:border-sky-300 dark:hover:border-sky-700';
                }
            });
        });
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-sky-500', 'bg-sky-50', 'dark:bg-sky-900/20');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-sky-500', 'bg-sky-50', 'dark:bg-sky-900/20');
        });
    });

    dropZone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showFilePreview(files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            showFilePreview(this.files[0]);
        }
    });

    removeFile.addEventListener('click', function() {
        fileInput.value = '';
        filePreview.classList.add('hidden');
        dropZone.classList.remove('hidden');
    });

    function showFilePreview(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        const ext = file.name.split('.').pop().toLowerCase();
        let iconHtml = '';
        let iconBg = 'bg-slate-200 dark:bg-slate-600';
        
        if (['pdf'].includes(ext)) {
            iconBg = 'bg-red-100 dark:bg-red-900/30';
            iconHtml = '<svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>';
        } else if (['doc', 'docx'].includes(ext)) {
            iconBg = 'bg-blue-100 dark:bg-blue-900/30';
            iconHtml = '<svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z"/></svg>';
        } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
            iconBg = 'bg-green-100 dark:bg-green-900/30';
            iconHtml = '<svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2z"/></svg>';
        } else {
            iconHtml = '<svg class="w-6 h-6 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6z"/></svg>';
        }
        
        fileIcon.className = 'w-12 h-12 rounded-lg flex items-center justify-center ' + iconBg;
        fileIcon.innerHTML = iconHtml;
        
        dropZone.classList.add('hidden');
        filePreview.classList.remove('hidden');
    }

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
