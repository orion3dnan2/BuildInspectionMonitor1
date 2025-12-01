@extends('layouts.app')

@section('title', 'تعديل المستند')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.documents.show', $document) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للمستند
    </a>
    <h1 class="text-2xl font-bold text-slate-800">تعديل المستند: {{ $document->document_number }}</h1>
    
    @if($document->status === 'needs_modification' && $document->modification_notes)
    <div class="mt-4 p-4 bg-orange-50 border border-orange-200 rounded-lg">
        <h3 class="font-bold text-orange-800 mb-2">ملاحظات التعديل المطلوبة:</h3>
        <p class="text-orange-700">{{ $document->modification_notes }}</p>
    </div>
    @endif
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">عنوان المستند <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $document->title) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('title') border-red-500 @enderror">
                @error('title')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">نوع المستند <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="letter" {{ old('type', $document->type) == 'letter' ? 'selected' : '' }}>كتاب</option>
                    <option value="memo" {{ old('type', $document->type) == 'memo' ? 'selected' : '' }}>مذكرة</option>
                    <option value="report" {{ old('type', $document->type) == 'report' ? 'selected' : '' }}>تقرير</option>
                    <option value="decision" {{ old('type', $document->type) == 'decision' ? 'selected' : '' }}>قرار</option>
                    <option value="circular" {{ old('type', $document->type) == 'circular' ? 'selected' : '' }}>تعميم</option>
                    <option value="other" {{ old('type', $document->type) == 'other' ? 'selected' : '' }}>أخرى</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الأولوية <span class="text-red-500">*</span></label>
                <select name="priority" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="low" {{ old('priority', $document->priority) == 'low' ? 'selected' : '' }}>منخفضة</option>
                    <option value="normal" {{ old('priority', $document->priority) == 'normal' ? 'selected' : '' }}>عادية</option>
                    <option value="high" {{ old('priority', $document->priority) == 'high' ? 'selected' : '' }}>عالية</option>
                    <option value="urgent" {{ old('priority', $document->priority) == 'urgent' ? 'selected' : '' }}>عاجلة</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">القسم</label>
                <select name="department_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="">-- اختر القسم --</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $document->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">مرفق (Word/PDF)</label>
                <input type="file" name="file" accept=".doc,.docx,.pdf,.rtf" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                @if($document->file_path)
                <p class="mt-1 text-xs text-slate-500">يوجد مرفق حالي. رفع ملف جديد سيستبدل المرفق الحالي.</p>
                @else
                <p class="mt-1 text-xs text-slate-500">الملفات المدعومة: DOC, DOCX, PDF, RTF - الحد الأقصى: 10MB</p>
                @endif
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">محتوى المستند <span class="text-red-500">*</span></label>
            <textarea name="content" id="content" rows="15" required class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 font-normal leading-relaxed @error('content') border-red-500 @enderror" style="min-height: 400px;">{{ old('content', $document->content) }}</textarea>
            @error('content')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                حفظ التغييرات
            </button>
            <a href="{{ route('admin.documents.show', $document) }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    #content {
        font-family: 'Tajawal', sans-serif;
        line-height: 2;
    }
</style>
@endpush
