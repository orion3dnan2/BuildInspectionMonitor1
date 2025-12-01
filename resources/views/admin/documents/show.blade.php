@extends('layouts.app')

@section('title', 'تفاصيل المستند')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للمستندات
    </a>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ $document->title }}</h1>
            <p class="text-slate-500 mt-1">{{ $document->document_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.documents.print', $document) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                طباعة
            </a>
            @if(in_array($document->status, ['draft', 'needs_modification']))
            <a href="{{ route('admin.documents.edit', $document) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل
            </a>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800">محتوى المستند</h2>
                @switch($document->status)
                    @case('draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">مسودة</span>
                        @break
                    @case('pending_review')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">قيد المراجعة</span>
                        @break
                    @case('pending_approval')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-sky-100 text-sky-800">قيد الاعتماد</span>
                        @break
                    @case('approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">معتمد</span>
                        @break
                    @case('rejected')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">مرفوض</span>
                        @break
                    @case('needs_modification')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">يحتاج تعديل</span>
                        @break
                @endswitch
            </div>
            
            <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $document->content }}</div>

            @if($document->signature_data && $document->status === 'approved')
            <div class="mt-6 pt-6 border-t border-slate-200">
                <h3 class="text-sm font-medium text-slate-700 mb-2">توقيع المدير</h3>
                <img src="{{ $document->signature_data }}" alt="التوقيع" class="max-w-xs border border-slate-200 rounded-lg">
                <p class="mt-2 text-sm text-slate-500">{{ $document->approver?->name }} - {{ $document->approved_at?->format('Y/m/d H:i') }}</p>
            </div>
            @endif
        </div>

        @if($document->rejection_reason)
        <div class="bg-red-50 rounded-xl border border-red-200 p-6">
            <h3 class="font-bold text-red-800 mb-2">سبب الرفض</h3>
            <p class="text-red-700">{{ $document->rejection_reason }}</p>
        </div>
        @endif

        @if($document->modification_notes && $document->status === 'needs_modification')
        <div class="bg-orange-50 rounded-xl border border-orange-200 p-6">
            <h3 class="font-bold text-orange-800 mb-2">ملاحظات التعديل المطلوبة</h3>
            <p class="text-orange-700">{{ $document->modification_notes }}</p>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">سجل سير العمل</h2>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse($document->workflows->sortByDesc('created_at') as $workflow)
                <div class="p-4 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                        @switch($workflow->action)
                            @case('submit') bg-sky-100 text-sky-600 @break
                            @case('review') bg-amber-100 text-amber-600 @break
                            @case('forward') bg-purple-100 text-purple-600 @break
                            @case('approve') bg-emerald-100 text-emerald-600 @break
                            @case('reject') bg-red-100 text-red-600 @break
                            @case('request_modification') bg-orange-100 text-orange-600 @break
                            @case('modify') bg-slate-100 text-slate-600 @break
                        @endswitch
                    ">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @switch($workflow->action)
                                @case('approve')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    @break
                                @case('reject')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    @break
                                @default
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            @endswitch
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-800">
                            <span class="font-medium">{{ $workflow->fromUser->name }}</span>
                            <span class="text-slate-500">{{ $workflow->action_label }}</span>
                            @if($workflow->action !== 'approve' && $workflow->action !== 'reject')
                            <span class="text-slate-500">إلى</span>
                            <span class="font-medium">{{ $workflow->toUser->name }}</span>
                            @endif
                        </p>
                        @if($workflow->comments)
                        <p class="mt-1 text-sm text-slate-600">{{ $workflow->comments }}</p>
                        @endif
                        <p class="mt-1 text-xs text-slate-400">{{ $workflow->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <div>
                        @if($workflow->status === 'completed')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">مكتمل</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">معلق</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-500">
                    لا يوجد سجل لسير العمل
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">معلومات المستند</h2>
            
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-slate-500">النوع</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $document->type_label }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">الأولوية</dt>
                    <dd>
                        @switch($document->priority)
                            @case('low')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">منخفضة</span>
                                @break
                            @case('normal')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">عادية</span>
                                @break
                            @case('high')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">عالية</span>
                                @break
                            @case('urgent')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">عاجلة</span>
                                @break
                        @endswitch
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">المنشئ</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $document->creator?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">القسم</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $document->department?->name ?? 'غير محدد' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">تاريخ الإنشاء</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $document->created_at->format('Y/m/d H:i') }}</dd>
                </div>
                @if($document->approved_at)
                <div>
                    <dt class="text-sm text-slate-500">تاريخ الاعتماد</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $document->approved_at->format('Y/m/d H:i') }}</dd>
                </div>
                @endif
                @if($document->file_path)
                <div>
                    <dt class="text-sm text-slate-500">المرفق</dt>
                    <dd>
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="inline-flex items-center gap-2 text-sky-600 hover:text-sky-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            تحميل المرفق
                        </a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        @if($document->status === 'draft' && $document->created_by === auth()->id())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">إرسال للمراجعة</h2>
            <form action="{{ route('admin.documents.send-for-review', $document) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">المراجع</label>
                    <select name="reviewer_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- اختر المراجع --</option>
                        @foreach(\App\Models\User::where('role', '!=', 'user')->where('id', '!=', auth()->id())->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات</label>
                    <textarea name="comments" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                    إرسال للمراجعة
                </button>
            </form>
        </div>
        @endif

        @if($document->status === 'pending_review' && $document->assigned_to === auth()->id())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">إجراءات المراجعة</h2>
            
            <form action="{{ route('admin.documents.send-to-manager', $document) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">المدير</label>
                    <select name="manager_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- اختر المدير --</option>
                        @foreach(\App\Models\User::where('role', 'admin')->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات</label>
                    <textarea name="comments" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                    إرسال للمدير للاعتماد
                </button>
            </form>

            <hr class="my-4">

            <form action="{{ route('admin.documents.request-modification', $document) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات التعديل</label>
                    <textarea name="modification_notes" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="اذكر التعديلات المطلوبة..."></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                    طلب تعديل
                </button>
            </form>
        </div>
        @endif

        @if($document->status === 'pending_approval' && $document->assigned_to === auth()->id())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">اعتماد المستند</h2>
            
            <form action="{{ route('admin.documents.approve', $document) }}" method="POST" id="approveForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">التوقيع الإلكتروني</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-lg p-4">
                        <canvas id="signatureCanvas" width="300" height="150" class="w-full bg-white rounded cursor-crosshair"></canvas>
                        <input type="hidden" name="signature_data" id="signatureData">
                    </div>
                    <button type="button" onclick="clearSignature()" class="mt-2 text-sm text-slate-500 hover:text-slate-700">مسح التوقيع</button>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات</label>
                    <textarea name="comments" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                    اعتماد المستند
                </button>
            </form>

            <hr class="my-4">

            <form action="{{ route('admin.documents.reject', $document) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">سبب الرفض</label>
                    <textarea name="rejection_reason" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                    رفض المستند
                </button>
            </form>

            <hr class="my-4">

            <form action="{{ route('admin.documents.request-modification', $document) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات التعديل</label>
                    <textarea name="modification_notes" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="اذكر التعديلات المطلوبة..."></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                    طلب تعديل
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
let canvas, ctx, isDrawing = false, lastX = 0, lastY = 0;

document.addEventListener('DOMContentLoaded', function() {
    canvas = document.getElementById('signatureCanvas');
    if (!canvas) return;
    
    ctx = canvas.getContext('2d');
    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', stopDrawing);

    document.getElementById('approveForm')?.addEventListener('submit', function(e) {
        document.getElementById('signatureData').value = canvas.toDataURL();
    });
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

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const rect = canvas.getBoundingClientRect();
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    
    if (e.type === 'touchstart') {
        isDrawing = true;
        [lastX, lastY] = [x, y];
    } else if (e.type === 'touchmove' && isDrawing) {
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.stroke();
        [lastX, lastY] = [x, y];
    }
}

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}
</script>
@endpush
