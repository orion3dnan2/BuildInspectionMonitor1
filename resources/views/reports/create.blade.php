@extends('layouts.app')

@section('title', 'إضافة تقرير - نظام التفتيش والمراقبة')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">إضافة تقرير جديد</h1>
    <p class="text-gray-600 mt-1">أدخل بيانات تقرير التفتيش</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('reports.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="record_number" class="block text-sm font-medium text-gray-700 mb-2">رقم السجل <span class="text-red-500">*</span></label>
                <input type="text" name="record_number" id="record_number" value="{{ old('record_number') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('record_number') border-red-500 @enderror">
                @error('record_number')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="outgoing_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الصادر</label>
                <input type="text" name="outgoing_number" id="outgoing_number" value="{{ old('outgoing_number') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('outgoing_number') border-red-500 @enderror">
                @error('outgoing_number')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="officer_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الضابط <span class="text-red-500">*</span></label>
                <input type="text" name="officer_name" id="officer_name" value="{{ old('officer_name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('officer_name') border-red-500 @enderror">
                @error('officer_name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">الرتبة</label>
                <input type="text" name="rank" id="rank" value="{{ old('rank') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rank') border-red-500 @enderror">
                @error('rank')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="office_name" class="block text-sm font-medium text-gray-700 mb-2">اسم المكتب <span class="text-red-500">*</span></label>
                <input type="text" name="office_name" id="office_name" value="{{ old('office_name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('office_name') border-red-500 @enderror">
                @error('office_name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="inspection_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ التفتيش <span class="text-red-500">*</span></label>
                <input type="date" name="inspection_date" id="inspection_date" value="{{ old('inspection_date', date('Y-m-d')) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('inspection_date') border-red-500 @enderror">
                @error('inspection_date')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
            <textarea name="notes" id="notes" rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
            @error('notes')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                حفظ التقرير
            </button>
            <a href="{{ route('reports.index') }}" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
