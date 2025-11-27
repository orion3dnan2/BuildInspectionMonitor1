@extends('layouts.app')

@section('title', 'عرض القيد - ' . $book->book_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('books.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mb-2">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للقائمة
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $book->book_number }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                @if($book->status == 'draft') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                @elseif($book->status == 'submitted') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                @elseif($book->status == 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                @elseif($book->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                @endif">
                {{ $book->status_label }}
            </span>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">بيانات القيد</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">رقم القيد</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->book_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">نوع الكتاب</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->book_type_label }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">عنوان الكتاب</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->book_title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">تاريخ الكتابة</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->date_written->format('Y/m/d') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">اسم الكاتب</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->writer_name }}</dd>
                </div>
                @if($book->writer_rank)
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">رتبة الكاتب</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->writer_rank }}</dd>
                </div>
                @endif
                @if($book->writer_office)
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">مكتب الكاتب</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $book->writer_office }}</dd>
                </div>
                @endif
                @if($book->description)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">الوصف</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white whitespace-pre-wrap">{{ $book->description }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">أنشئ بواسطة</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $book->creator->name ?? 'غير معروف' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">تاريخ الإنشاء</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $book->created_at->format('Y/m/d H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($book->manager_comment)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-6">
        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-2">ملاحظات المدير</h3>
        <p class="text-yellow-700 dark:text-yellow-400 whitespace-pre-wrap">{{ $book->manager_comment }}</p>
    </div>
    @endif

    @if($book->status == 'approved' && $book->latestSignature)
    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-6">
        <h3 class="text-lg font-semibold text-green-800 dark:text-green-300 mb-4">توقيع الاعتماد</h3>
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-green-600 dark:text-green-400">معتمد بواسطة</dt>
                        <dd class="font-medium text-green-800 dark:text-green-300">{{ $book->approver->name ?? 'غير معروف' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-green-600 dark:text-green-400">تاريخ الاعتماد</dt>
                        <dd class="font-medium text-green-800 dark:text-green-300">{{ $book->approved_at?->format('Y/m/d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-green-600 dark:text-green-400">رمز التحقق</dt>
                        <dd class="font-mono text-xs text-green-700 dark:text-green-400">{{ substr($book->latestSignature->signature_hash, 0, 16) }}...</dd>
                    </div>
                </dl>
            </div>
            @if($book->latestSignature->signature_data)
            <div class="flex-shrink-0">
                <p class="text-sm text-green-600 dark:text-green-400 mb-2">التوقيع الإلكتروني</p>
                <div class="bg-white dark:bg-slate-800 border border-green-300 dark:border-green-700 rounded-lg p-2">
                    <img src="{{ $book->latestSignature->signature_data }}" alt="التوقيع" class="max-w-[200px] h-auto">
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="flex flex-wrap gap-3">
        @if($book->status == 'draft')
        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
        <form method="POST" action="{{ route('books.send', $book) }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                إرسال للمدير
            </button>
        </form>
        @endif

        @if($book->status == 'needs_modification')
        <a href="{{ route('books.edit', $book) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل وإعادة الإرسال
        </a>
        @endif

        @if($book->status == 'submitted' && in_array(auth()->user()->role, ['admin', 'supervisor']))
        <button type="button" onclick="openApproveModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            اعتماد مع التوقيع
        </button>
        <button type="button" onclick="openRejectModal()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            رفض
        </button>
        <button type="button" onclick="openEditRequestModal()" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            طلب تعديل
        </button>
        @endif

        @if($book->status == 'approved')
        <a href="{{ route('books.print', $book) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            طباعة
        </a>
        @endif
    </div>
</div>

@if($book->status == 'submitted' && in_array(auth()->user()->role, ['admin', 'supervisor']))
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-lg w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">اعتماد القيد مع التوقيع الإلكتروني</h3>
        </div>
        <form method="POST" action="{{ route('books.approve', $book) }}">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التوقيع الإلكتروني</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-lg p-2">
                        <canvas id="signatureCanvas" width="400" height="150" class="w-full bg-white dark:bg-slate-700 rounded cursor-crosshair"></canvas>
                    </div>
                    <input type="hidden" name="signature" id="signatureInput">
                    <button type="button" onclick="clearSignature()" class="mt-2 text-sm text-red-600 hover:text-red-800">مسح التوقيع</button>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ملاحظات (اختياري)</label>
                    <textarea name="comments" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                    إلغاء
                </button>
                <button type="submit" onclick="saveSignature()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    اعتماد
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-lg w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">رفض القيد</h3>
        </div>
        <form method="POST" action="{{ route('books.reject', $book) }}">
            @csrf
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">سبب الرفض <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="4" required minlength="10" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white"></textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">يجب أن يكون السبب 10 أحرف على الأقل</p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                    إلغاء
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    رفض
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editRequestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-lg w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">طلب تعديل</h3>
        </div>
        <form method="POST" action="{{ route('books.request-edit', $book) }}">
            @csrf
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التعديلات المطلوبة <span class="text-red-500">*</span></label>
                <textarea name="reason" rows="4" required minlength="10" class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white"></textarea>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">يجب أن يكون الوصف 10 أحرف على الأقل</p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700 flex justify-end gap-3">
                <button type="button" onclick="closeEditRequestModal()" class="px-4 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                    إلغاء
                </button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                    إرسال
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let canvas, ctx, isDrawing = false, lastX = 0, lastY = 0;

document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('signatureCanvas');
    if (canvas) {
        ctx = canvas.getContext('2d');
        ctx.strokeStyle = '#1a365d';
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);
        
        canvas.addEventListener('touchstart', handleTouchStart);
        canvas.addEventListener('touchmove', handleTouchMove);
        canvas.addEventListener('touchend', stopDrawing);
    }
});

function startDrawing(e) {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

function draw(e) {
    if (!isDrawing) return;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(e.offsetX, e.offsetY);
    ctx.stroke();
    [lastX, lastY] = [e.offsetX, e.offsetY];
}

function stopDrawing() {
    isDrawing = false;
}

function handleTouchStart(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    isDrawing = true;
    lastX = touch.clientX - rect.left;
    lastY = touch.clientY - rect.top;
}

function handleTouchMove(e) {
    e.preventDefault();
    if (!isDrawing) return;
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.stroke();
    lastX = x;
    lastY = y;
}

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function saveSignature() {
    document.getElementById('signatureInput').value = canvas.toDataURL();
}

function openApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveModal').classList.add('flex');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approveModal').classList.remove('flex');
    clearSignature();
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}

function openEditRequestModal() {
    document.getElementById('editRequestModal').classList.remove('hidden');
    document.getElementById('editRequestModal').classList.add('flex');
}

function closeEditRequestModal() {
    document.getElementById('editRequestModal').classList.add('hidden');
    document.getElementById('editRequestModal').classList.remove('flex');
}
</script>
@endif
@endsection
