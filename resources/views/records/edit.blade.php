@extends('layouts.app')

@section('title', 'تعديل السجل - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">تعديل السجل</h1>
    <p class="text-gray-600">رقم الصادر: {{ $record->record_number }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('records.update', $record) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label for="record_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الصادر <span class="text-red-500">*</span></label>
                <input type="text" name="record_number" id="record_number" value="{{ old('record_number', $record->record_number) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('record_number') border-red-500 @enderror">
                @error('record_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="military_id" class="block text-sm font-medium text-gray-700 mb-2">الرقم العسكري <span class="text-red-500">*</span></label>
                <input type="text" name="military_id" id="military_id" value="{{ old('military_id', $record->military_id) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('military_id') border-red-500 @enderror">
                @error('military_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="round_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الجولة <span class="text-red-500">*</span></label>
                <input type="date" name="round_date" id="round_date" value="{{ old('round_date', $record->round_date->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('round_date') border-red-500 @enderror">
                @error('round_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $record->first_name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror">
                @error('first_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="second_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الثاني</label>
                <input type="text" name="second_name" id="second_name" value="{{ old('second_name', $record->second_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="third_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الثالث</label>
                <input type="text" name="third_name" id="third_name" value="{{ old('third_name', $record->third_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="fourth_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الرابع</label>
                <input type="text" name="fourth_name" id="fourth_name" value="{{ old('fourth_name', $record->fourth_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">الرتبة</label>
                <select name="rank" id="rank" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر الرتبة</option>
                    @foreach($ranks as $rank)
                        <option value="{{ $rank }}" {{ old('rank', $record->rank) == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="governorate" class="block text-sm font-medium text-gray-700 mb-2">المحافظة</label>
                <select name="governorate" id="governorate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر المحافظة</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov }}" {{ old('governorate', $record->governorate) == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="station" class="block text-sm font-medium text-gray-700 mb-2">المخفر</label>
                <select name="station" id="station" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر المخفر</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->name }}" {{ old('station', $record->station) == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="action_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الإجراء</label>
                <select name="action_type" id="action_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر نوع الإجراء</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ old('action_type', $record->action_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="ports" class="block text-sm font-medium text-gray-700 mb-2">المنافذ</label>
                <select name="ports" id="ports" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر المنفذ</option>
                    @foreach($ports as $port)
                        <option value="{{ $port->name }}" {{ old('ports', $record->ports) == $port->name ? 'selected' : '' }}>{{ $port->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">الملاحظات</label>
            <textarea name="notes" id="notes" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $record->notes) }}</textarea>
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                حفظ التعديلات
            </button>
            <a href="{{ route('records.index') }}" class="text-gray-600 hover:text-gray-800">إلغاء</a>
        </div>
    </form>
</div>
@endsection
