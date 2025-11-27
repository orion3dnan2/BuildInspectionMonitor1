@extends('layouts.app')

@section('title', 'تعديل القيد - ' . $book->book_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            العودة للعرض
        </a>
    </div>

    @if($book->status == 'needs_modification' && $book->manager_comment)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800 p-6 mb-6">
        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-2">التعديلات المطلوبة</h3>
        <p class="text-yellow-700 dark:text-yellow-400 whitespace-pre-wrap">{{ $book->manager_comment }}</p>
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">تعديل القيد: {{ $book->book_number }}</h2>
        </div>
        
        <form method="POST" action="{{ route('books.update', $book) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رقم القيد</label>
                    <input type="text" value="{{ $book->book_number }}" readonly
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-600 text-gray-900 dark:text-white">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الكتاب <span class="text-red-500">*</span></label>
                    <select name="book_type" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="incoming" {{ old('book_type', $book->book_type) == 'incoming' ? 'selected' : '' }}>وارد</option>
                        <option value="outgoing" {{ old('book_type', $book->book_type) == 'outgoing' ? 'selected' : '' }}>صادر</option>
                        <option value="internal" {{ old('book_type', $book->book_type) == 'internal' ? 'selected' : '' }}>داخلي</option>
                        <option value="circular" {{ old('book_type', $book->book_type) == 'circular' ? 'selected' : '' }}>تعميم</option>
                        <option value="decision" {{ old('book_type', $book->book_type) == 'decision' ? 'selected' : '' }}>قرار</option>
                    </select>
                    @error('book_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">عنوان الكتاب <span class="text-red-500">*</span></label>
                    <input type="text" name="book_title" value="{{ old('book_title', $book->book_title) }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('book_title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تاريخ الكتابة <span class="text-red-500">*</span></label>
                    <input type="date" name="date_written" value="{{ old('date_written', $book->date_written->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('date_written')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم الكاتب <span class="text-red-500">*</span></label>
                    <input type="text" name="writer_name" value="{{ old('writer_name', $book->writer_name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    @error('writer_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رتبة الكاتب</label>
                    <input type="text" name="writer_rank" value="{{ old('writer_rank', $book->writer_rank) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">مكتب الكاتب</label>
                    <input type="text" name="writer_office" value="{{ old('writer_office', $book->writer_office) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الوصف</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('description', $book->description) }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                <a href="{{ route('books.show', $book) }}" class="px-6 py-2 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
