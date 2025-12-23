@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.documents.index') }}" class="text-slate-500 hover:text-slate-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">{{ $document->title }}</h1>
    </div>
    <div class="flex items-center gap-2">
        @if($document->getViewablePdfPath())
            <a href="{{ route('admin.documents.download', $document) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تحميل PDF
            </a>
        @endif
        @if(in_array($document->status, ['draft', 'needs_modification']))
            <a href="{{ route('admin.documents.edit', $document) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل
            </a>
        @endif
        <a href="{{ route('admin.documents.print', $document) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            طباعة
        </a>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">تفاصيل المستند</h2>
                @switch($document->status)
                    @case('draft')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">مسودة</span>
                        @break
                    @case('under_review')
                    @case('pending_review')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">قيد المراجعة</span>
                        @break
                    @case('signed')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">موقّع</span>
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
                    @case('archived')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">مؤرشف</span>
                        @break
                @endswitch
            </div>
            
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-slate-500">رقم المستند</span>
                        <p class="font-medium text-slate-800">{{ $document->document_number }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">النوع</span>
                        <p class="font-medium text-slate-800">{{ $document->type_label }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">الأولوية</span>
                        <p class="font-medium text-slate-800">{{ $document->priority_label }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">القسم</span>
                        <p class="font-medium text-slate-800">{{ $document->department?->name ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">منشئ المستند</span>
                        <p class="font-medium text-slate-800">{{ $document->creator?->name ?? 'غير معروف' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">تاريخ الإنشاء</span>
                        <p class="font-medium text-slate-800">{{ $document->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>

                @if($document->content)
                <div class="pt-4 border-t border-slate-100">
                    <span class="text-sm text-slate-500">المحتوى</span>
                    <div class="mt-2 prose prose-sm max-w-none text-slate-700">
                        {!! nl2br(e($document->content)) !!}
                    </div>
                </div>
                @endif

                @if($document->is_signed)
                <div class="pt-4 border-t border-slate-100 bg-blue-50 -mx-6 px-6 py-4 -mb-6">
                    <div class="flex items-center gap-2 text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">تم التوقيع</span>
                    </div>
                    <p class="text-sm text-blue-600 mt-1">
                        بواسطة: {{ $document->signer?->name ?? 'غير معروف' }} - {{ $document->signed_at?->format('Y/m/d H:i') }}
                    </p>
                </div>
                @endif
            </div>
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
                <h2 class="text-lg font-bold text-slate-800">الإجراءات</h2>
            </div>
            <div class="p-6 space-y-4">
                @if($document->status === 'draft')
                    <form action="{{ route('admin.documents.send-for-review', $document) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">إرسال للمراجعة إلى</label>
                            <select name="reviewer_id" required class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500">
                                <option value="">اختر المراجع</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات (اختياري)</label>
                            <textarea name="comments" rows="2" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500"></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                            إرسال للمراجعة
                        </button>
                    </form>
                @endif

                @if(in_array($document->status, ['under_review', 'pending_review', 'signed']))
                    <form action="{{ route('admin.documents.send-to-manager', $document) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">إرسال للمدير للاعتماد</label>
                            <select name="manager_id" required class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500">
                                <option value="">اختر المدير</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات (اختياري)</label>
                            <textarea name="comments" rows="2" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500"></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                            إرسال للاعتماد
                        </button>
                    </form>
                @endif

                @if($document->status === 'pending_approval')
                    <div class="space-y-4">
                        <form action="{{ route('admin.documents.approve', $document) }}" method="POST" id="approveForm" class="space-y-4">
                            @csrf
                            <input type="hidden" name="signature_data" id="approveSignatureData">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات الاعتماد (اختياري)</label>
                                <textarea name="comments" rows="2" class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                                اعتماد المستند
                            </button>
                        </form>

                        <form action="{{ route('admin.documents.reject', $document) }}" method="POST" class="space-y-4 pt-4 border-t border-slate-200">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">سبب الرفض</label>
                                <textarea name="rejection_reason" rows="2" required class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                                رفض المستند
                            </button>
                        </form>
                    </div>
                @endif

                @if(in_array($document->status, ['under_review', 'pending_review', 'pending_approval']))
                    <form action="{{ route('admin.documents.request-modification', $document) }}" method="POST" class="space-y-4 pt-4 border-t border-slate-200">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">طلب تعديل</label>
                            <textarea name="modification_notes" rows="2" required class="w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" placeholder="أدخل ملاحظات التعديل المطلوبة"></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition">
                            طلب تعديل
                        </button>
                    </form>
                @endif

                @if($document->status === 'approved')
                    <form action="{{ route('admin.documents.archive', $document) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition" onclick="return confirm('هل أنت متأكد من أرشفة هذا المستند؟')">
                            أرشفة المستند
                        </button>
                    </form>
                @endif
            </div>
        </div>

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
                            @case('forward') bg-indigo-100 text-indigo-600 @break
                            @case('sign') bg-blue-100 text-blue-600 @break
                            @case('approve') bg-emerald-100 text-emerald-600 @break
                            @case('reject') bg-red-100 text-red-600 @break
                            @case('request_modification') bg-orange-100 text-orange-600 @break
                            @case('archive') bg-purple-100 text-purple-600 @break
                            @default bg-slate-100 text-slate-600
                        @endswitch
                    ">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @switch($workflow->action)
                                @case('submit')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    @break
                                @case('approve')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @break
                                @case('reject')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @break
                                @case('sign')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    @break
                                @default
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            @endswitch
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-800">
                                @switch($workflow->action)
                                    @case('submit') إرسال للمراجعة @break
                                    @case('review') مراجعة @break
                                    @case('forward') تحويل @break
                                    @case('sign') توقيع @break
                                    @case('approve') اعتماد @break
                                    @case('reject') رفض @break
                                    @case('request_modification') طلب تعديل @break
                                    @case('archive') أرشفة @break
                                    @default {{ $workflow->action }}
                                @endswitch
                            </span>
                            <span class="text-sm text-slate-500">{{ $workflow->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">
                            من: {{ $workflow->fromUser?->name ?? 'غير معروف' }}
                            @if($workflow->toUser)
                                → إلى: {{ $workflow->toUser->name }}
                            @endif
                        </p>
                        @if($workflow->comments)
                            <p class="text-sm text-slate-500 mt-1 bg-slate-50 rounded p-2">{{ $workflow->comments }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-slate-500">
                    لا يوجد سجل سير عمل حتى الآن
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800">عرض المستند</h2>
                <div class="flex items-center gap-2">
                    @if($document->getViewablePdfPath())
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs rounded">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                        </svg>
                        PDF
                    </span>
                    @elseif($document->isWordDocument())
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11z"/>
                        </svg>
                        Word
                    </span>
                    @endif
                    @if($document->hasViewableFile())
                    <a href="{{ route('admin.documents.download', $document) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تحميل
                    </a>
                    @endif
                </div>
            </div>
            
            @if($document->getViewablePdfPath())
            <div class="relative bg-slate-100" style="height: 700px;">
                <iframe 
                    id="pdfViewer"
                    src="/pdfjs/web/viewer.html?file={{ urlencode(route('admin.documents.pdf', $document)) }}"
                    width="100%" 
                    height="100%" 
                    style="border: none;"
                    allowfullscreen>
                </iframe>
            </div>
            @elseif($document->isWordDocument())
            <div id="wordContainer" class="relative bg-white" style="height: 700px; overflow: auto;">
                <div id="wordLoading" class="absolute inset-0 flex items-center justify-center bg-slate-50">
                    <div class="text-center">
                        <svg class="animate-spin w-10 h-10 mx-auto text-blue-500 mb-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-slate-600">جاري تحميل المستند...</p>
                    </div>
                </div>
                <div id="wordContent" class="p-4" dir="rtl" style="font-family: 'Amiri', 'Arabic Typesetting', 'Traditional Arabic', serif;"></div>
            </div>
            @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-4 text-slate-500">لا يوجد ملف مرفق</p>
            </div>
            @endif
        </div>

        @if($document->canBeSigned() && !$document->is_signed)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">التوقيع الإلكتروني</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.documents.sign', $document) }}" method="POST" enctype="multipart/form-data" id="signatureForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">رفع صورة التوقيع</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 p-6 text-center" id="dropZone">
                            <input type="file" name="signature_image" id="signatureImage" accept="image/png,image/jpeg,image/gif" class="hidden" required>
                            <div id="uploadPrompt">
                                <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-slate-600 mb-2">اضغط لاختيار صورة التوقيع</p>
                                <p class="text-xs text-slate-400">PNG, JPG, GIF - الحد الأقصى 2MB</p>
                            </div>
                            <div id="previewContainer" class="hidden">
                                <img id="signaturePreview" class="max-h-32 mx-auto rounded border border-slate-200" alt="معاينة التوقيع">
                                <button type="button" onclick="clearSignatureImage()" class="mt-2 text-sm text-red-500 hover:text-red-700">إزالة الصورة</button>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">سيتم إضافة التوقيع في آخر صفحة من المستند</p>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitBtn" disabled>
                        توقيع المستند
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($document->signature_data && $document->is_signed)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">التوقيع</h2>
            </div>
            <div class="p-6">
                <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                    <img src="{{ route('admin.documents.signature', $document) }}" alt="التوقيع" class="max-w-full mx-auto max-h-24">
                </div>
                <p class="mt-2 text-sm text-slate-500 text-center">
                    {{ $document->signer?->name }} - {{ $document->signed_at?->format('Y/m/d H:i') }}
                </p>
            </div>
        </div>
        @endif
    </div>
</div>

@if($document->isWordDocument() && !$document->getViewablePdfPath())
<script src="https://unpkg.com/docx-preview@0.3.3/dist/docx-preview.min.js"></script>
<script>
    function loadWordDocument() {
        const container = document.getElementById('wordContent');
        const loading = document.getElementById('wordLoading');
        const wordUrl = '{{ route("admin.documents.word", $document) }}';
        
        if (typeof window.docx === 'undefined') {
            setTimeout(loadWordDocument, 100);
            return;
        }
        
        fetch(wordUrl)
            .then(response => {
                if (!response.ok) throw new Error('فشل في تحميل المستند');
                return response.arrayBuffer();
            })
            .then(arrayBuffer => {
                return window.docx.renderAsync(arrayBuffer, container, null, {
                    className: 'docx-wrapper',
                    inWrapper: true,
                    ignoreWidth: false,
                    ignoreHeight: false,
                    ignoreFonts: false,
                    breakPages: true,
                    ignoreLastRenderedPageBreak: true,
                    experimental: true,
                    useBase64URL: true
                });
            })
            .then(() => {
                loading.style.display = 'none';
            })
            .catch(error => {
                console.error('Error loading Word document:', error);
                loading.innerHTML = '<div class="text-center"><p class="text-red-500">فشل في تحميل المستند</p><p class="text-sm text-slate-500 mt-2">' + error.message + '</p></div>';
            });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadWordDocument);
    } else {
        loadWordDocument();
    }
</script>
<style>
    #wordContent .docx-wrapper {
        background: white;
        padding: 20px;
    }
    #wordContent .docx-wrapper > section.docx {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: 10px auto;
        background: white;
        direction: rtl;
    }
</style>
@endif

@if($document->canBeSigned() && !$document->is_signed)
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('signatureImage');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const previewContainer = document.getElementById('previewContainer');
    const signaturePreview = document.getElementById('signaturePreview');
    const submitBtn = document.getElementById('submitBtn');
    
    dropZone.addEventListener('click', () => fileInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });
    
    function handleFileSelect(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            signaturePreview.src = e.target.result;
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            submitBtn.disabled = false;
        };
        reader.readAsDataURL(file);
    }
    
    window.clearSignatureImage = function() {
        fileInput.value = '';
        signaturePreview.src = '';
        uploadPrompt.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        submitBtn.disabled = true;
    }
</script>
@endif
@endsection
