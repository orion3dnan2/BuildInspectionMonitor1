@extends('layouts.app')

@section('title', 'عرض الكتاب - ' . $correspondence->document_number)

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.correspondences.show', $correspondence) }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-white text-sm mb-4 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للتفاصيل
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $correspondence->title }}</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">رقم الكتاب: {{ $correspondence->document_number }}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $correspondence->type === 'incoming' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-400' }}">
                    {{ $correspondence->type_name }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $correspondence->status_color }}-100 text-{{ $correspondence->status_color }}-800 dark:bg-{{ $correspondence->status_color }}-900/30 dark:text-{{ $correspondence->status_color }}-400">
                    {{ $correspondence->status_name }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">معاينة الكتاب</h2>
                    @if($correspondence->file_path)
                    <a href="{{ route('admin.correspondences.download', $correspondence) }}" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        تحميل
                    </a>
                    @endif
                </div>
                <div class="p-4">
                    @if($correspondence->file_path)
                        @if($correspondence->isPdf())
                        <iframe src="{{ asset('storage/' . $correspondence->file_path) }}" class="w-full h-[700px] rounded-lg border border-slate-200 dark:border-slate-700"></iframe>
                        @elseif($correspondence->isImage())
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $correspondence->file_path) }}" alt="{{ $correspondence->title }}" class="max-w-full h-auto rounded-lg max-h-[700px]">
                        </div>
                        @elseif($correspondence->isWord())
                        <div class="text-center py-16 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                            <svg class="w-20 h-20 mx-auto text-blue-500 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6C4.9,2,4,2.9,4,4v16c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8L14,2z M15.2,14.9L14,18h-1l-1.2-3.1L10.6,18h-1l-1.5-5h1 l0.9,3.3L11.2,13h0.6l1.2,3.3l0.9-3.3h1L15.2,14.9z M13,9V3.5L18.5,9H13z"/></svg>
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
                        </div>
                        @else
                        <div class="text-center py-16 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                            <svg class="w-20 h-20 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    @else
                    <div class="text-center py-16 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                        <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-slate-500 dark:text-slate-400">لا يوجد ملف مرفق لهذا الكتاب</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($correspondence->description)
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">محتوى المستند</h2>
                </div>
                <div class="p-4">
                    <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">{{ $correspondence->description }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">إضافة توقيع إلكتروني</h2>
                </div>
                <form action="{{ route('admin.correspondences.sign', $correspondence) }}" method="POST" id="signatureForm">
                    @csrf
                    <div class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">نوع الإجراء</label>
                            <select name="action" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent" required>
                                <option value="reviewed">مراجعة</option>
                                <option value="approved">اعتماد</option>
                                <option value="rejected">رفض</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">ملاحظات (اختياري)</label>
                            <textarea name="comments" rows="2" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-700 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-transparent resize-none" placeholder="أدخل ملاحظاتك هنا..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">التوقيع</label>
                            <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 relative">
                                <canvas id="signatureCanvas" class="w-full h-32 cursor-crosshair rounded-lg"></canvas>
                            </div>
                            <input type="hidden" name="signature_data" id="signatureData" required>
                            <button type="button" id="clearSignature" class="mt-2 text-sm text-red-500 hover:text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                مسح التوقيع
                            </button>
                            @error('signature_data')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full px-4 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            حفظ التوقيع
                        </button>
                    </div>
                </form>
            </div>

            @if($correspondence->signatures->count() > 0)
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">التوقيعات السابقة</h2>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($correspondence->signatures as $signature)
                    <div class="border border-slate-200 dark:border-slate-700 rounded-lg p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-slate-800 dark:text-white text-sm">{{ $signature->user->name ?? 'مستخدم' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                @if($signature->action === 'approved') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                                @elseif($signature->action === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                @else bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                @endif">
                                {{ $signature->action_label }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <img src="{{ $signature->signature_data }}" alt="التوقيع" class="h-12 bg-white rounded border border-slate-200">
                        </div>
                        @if($signature->comments)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">{{ $signature->comments }}</p>
                        @endif
                        <p class="text-xs text-slate-400">{{ $signature->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">معلومات الكتاب</h2>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">التاريخ</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->document_date->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">{{ $correspondence->type === 'incoming' ? 'الجهة المرسلة' : 'الجهة المستلمة' }}</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->type === 'incoming' ? ($correspondence->from_department ?: '-') : ($correspondence->to_department ?: '-') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                        <span class="text-sm text-slate-500 dark:text-slate-400">أنشأ بواسطة</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->creator->name ?? '-' }}</span>
                    </div>
                    @if($correspondence->file_path)
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400">حجم الملف</span>
                        <span class="text-sm text-slate-800 dark:text-white font-medium">{{ $correspondence->formatted_file_size }}</span>
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
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    const signatureData = document.getElementById('signatureData');
    const clearBtn = document.getElementById('clearSignature');
    const form = document.getElementById('signatureForm');
    
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width;
        canvas.height = rect.height;
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    function getPosition(e) {
        const rect = canvas.getBoundingClientRect();
        if (e.touches) {
            return {
                x: e.touches[0].clientX - rect.left,
                y: e.touches[0].clientY - rect.top
            };
        }
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        const pos = getPosition(e);
        lastX = pos.x;
        lastY = pos.y;
        e.preventDefault();
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        
        const pos = getPosition(e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        
        lastX = pos.x;
        lastY = pos.y;
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            signatureData.value = canvas.toDataURL('image/png');
        }
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    clearBtn.addEventListener('click', function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureData.value = '';
    });

    form.addEventListener('submit', function(e) {
        if (!signatureData.value) {
            e.preventDefault();
            alert('الرجاء إضافة توقيعك قبل الحفظ');
            return false;
        }
    });
});
</script>
@endpush
@endsection
