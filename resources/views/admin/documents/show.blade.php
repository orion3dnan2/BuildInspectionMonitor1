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
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
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
            
            @if($document->file_path)
                <div id="documentPreview" class="w-full" style="min-height: 600px; overflow-y: auto; background: #f5f5f5;">
                    @php
                        $fileExtension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                    @endphp
                    
                    @if(strtolower($fileExtension) === 'pdf')
                        <iframe src="{{ Storage::url($document->file_path) }}#toolbar=0" 
                                width="100%" 
                                height="600" 
                                style="border: none; display: block;">
                        </iframe>
                    @elseif(strtolower($fileExtension) === 'docx')
                        <div id="docxContainer" class="p-6 bg-white" style="min-height: 600px;"></div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const fileUrl = '{{ Storage::url($document->file_path) }}';
                                fetch(fileUrl)
                                    .then(response => response.arrayBuffer())
                                    .then(buffer => {
                                        const options = {
                                            className: 'docx-content',
                                            style: `
                                                .docx-content { font-family: 'Tajawal', Arial, sans-serif; padding: 20px; }
                                                .docx-content p { margin: 0.5rem 0; line-height: 1.6; }
                                                .docx-content h1, .docx-content h2, .docx-content h3 { margin: 1rem 0 0.5rem 0; font-weight: bold; }
                                                .docx-content table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
                                                .docx-content td, .docx-content th { border: 1px solid #ddd; padding: 8px; text-align: right; }
                                                .docx-content th { background-color: #f5f5f5; font-weight: bold; }
                                            `
                                        };
                                        docx.renderAsync(buffer, document.getElementById('docxContainer'), null, options);
                                    })
                                    .catch(error => {
                                        document.getElementById('docxContainer').innerHTML = `
                                            <div class="text-center p-6">
                                                <p class="text-red-600 font-medium">حدث خطأ في تحميل الملف</p>
                                                <p class="text-slate-600 text-sm mt-2">يرجى تحميل الملف مباشرة</p>
                                            </div>
                                        `;
                                        console.error('Error loading DOCX:', error);
                                    });
                            });
                        </script>
                    @else
                        <div class="p-6 bg-white text-center">
                            <p class="text-slate-600">نوع الملف: {{ strtoupper($fileExtension) }}</p>
                            <p class="text-slate-500 text-sm mt-2">نوع الملف غير مدعوم للعرض المباشر</p>
                            <a href="{{ Storage::url($document->file_path) }}" download class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                تحميل الملف
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-6">
                    <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $document->content }}</div>
                </div>
            @endif

            @if($document->signature_data && $document->status === 'approved')
            <div class="mt-6 pt-6 px-6 pb-6 border-t border-slate-200">
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
                    <dd class="space-y-2">
                        <button onclick="openFileViewer('{{ Storage::url($document->file_path) }}', '{{ pathinfo($document->file_path, PATHINFO_EXTENSION) }}')" class="inline-flex items-center gap-2 px-3 py-2 bg-sky-100 hover:bg-sky-200 text-sky-700 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            عرض الملف
                        </button>
                        <a href="{{ Storage::url($document->file_path) }}" download class="inline-flex items-center gap-2 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            تحميل
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

<!-- File Viewer Modal -->
<div id="fileViewerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800">عرض الملف</h3>
            <button onclick="closeFileViewer()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="fileViewerContent" class="flex-1 overflow-auto bg-slate-50">
            <!-- Content will be inserted here -->
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/docx-preview@0.1.38/build/index.umd.js"></script>
<script>
let canvas, ctx, isDrawing = false, lastX = 0, lastY = 0;

function openFileViewer(fileUrl, extension) {
    const modal = document.getElementById('fileViewerModal');
    const content = document.getElementById('fileViewerContent');
    extension = extension.toLowerCase();

    // Clear previous content
    content.innerHTML = '';

    if (extension === 'pdf') {
        // Use PDF.js to display PDF
        content.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <iframe src="${fileUrl}#toolbar=0" width="100%" height="100%" style="border: none; min-height: 600px;"></iframe>
            </div>
        `;
    } else if (extension === 'docx') {
        // Use docx-preview for Word documents
        content.innerHTML = `
            <div class="p-6 bg-white">
                <div id="docxContainer" class="prose prose-lg max-w-none"></div>
                <div class="mt-4 text-center">
                    <p class="text-sm text-slate-500 mb-2">جاري تحميل الملف...</p>
                </div>
            </div>
        `;
        
        // Fetch and render DOCX
        fetch(fileUrl)
            .then(response => response.arrayBuffer())
            .then(buffer => {
                const options = {
                    className: 'docx-container',
                    style: `
                        .docx-container { max-width: 100%; padding: 20px; }
                        .docx-container p { margin: 0.5rem 0; }
                        .docx-container table { border-collapse: collapse; width: 100%; }
                        .docx-container td, .docx-container th { border: 1px solid #ddd; padding: 8px; }
                    `
                };
                docx.renderAsync(buffer, document.getElementById('docxContainer'), null, options);
            })
            .catch(error => {
                document.getElementById('docxContainer').innerHTML = `
                    <div class="text-center p-6">
                        <p class="text-red-600 font-medium">حدث خطأ في تحميل الملف</p>
                        <p class="text-slate-600 text-sm mt-2">يرجى تحميل الملف مباشرة</p>
                    </div>
                `;
                console.error('Error loading DOCX:', error);
            });
    } else if (extension === 'doc') {
        // For old .doc files, offer download only
        content.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-slate-800 mb-2">صيغة Word قديمة</h4>
                    <p class="text-slate-600 mb-4">النظام يدعم ملفات DOCX فقط (Word 2007 وما بعده)</p>
                    <a href="${fileUrl}" download class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تحميل الملف
                    </a>
                </div>
            </div>
        `;
    } else {
        // Unsupported format
        content.innerHTML = `
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-slate-800 mb-2">صيغة الملف غير مدعومة</h4>
                    <p class="text-slate-600 mb-4">يدعم النظام عرض ملفات PDF و DOCX فقط</p>
                    <a href="${fileUrl}" download class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        تحميل الملف
                    </a>
                </div>
            </div>
        `;
    }

    modal.classList.remove('hidden');
}

function closeFileViewer() {
    const modal = document.getElementById('fileViewerModal');
    modal.classList.add('hidden');
}

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

    // Close modal when clicking outside
    document.getElementById('fileViewerModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeFileViewer();
        }
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
