@extends('layouts.app')

@section('title', 'إدخال البيانات - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm mb-4">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للصفحة الرئيسية
        </a>
        <h1 class="text-2xl font-bold text-gray-800">إدخال البيانات</h1>
        <p class="text-gray-500 text-sm">إضافة وتعديل البلاغات والقيود</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 text-center mb-6 text-red-700">إضافة سجل جديد</h2>
        
        <form action="{{ route('records.store') }}" method="POST" id="recordForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="record_number" class="block text-sm font-medium text-gray-700 mb-2 text-left">رقم الصادر <span class="text-red-500">*</span></label>
                    <input type="text" name="record_number" id="record_number" value="{{ old('record_number') }}" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('record_number') border-red-500 @enderror"
                        placeholder="">
                    @error('record_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="military_id" class="block text-sm font-medium text-gray-700 mb-2 text-left">الرقم العسكري</label>
                    <input type="text" name="military_id" id="military_id" value="{{ old('military_id') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('military_id') border-red-500 @enderror"
                        placeholder="">
                    @error('military_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="action_type" class="block text-sm font-medium text-gray-700 mb-2 text-left">نوع الإجراء في حقه</label>
                    <input type="text" name="action_type" id="action_type" value="{{ old('action_type') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="">
                </div>

                <div>
                    <label for="rank" class="block text-sm font-medium text-gray-700 mb-2 text-left">الرتبة <span class="text-red-500">*</span></label>
                    <select name="rank" id="rank" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">اختر الرتبة</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ old('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 text-left">الاسم <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-4 gap-3">
                    <div>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                            placeholder="الاسم الأول">
                    </div>
                    <div>
                        <input type="text" name="second_name" id="second_name" value="{{ old('second_name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="الاسم الثاني">
                    </div>
                    <div>
                        <input type="text" name="third_name" id="third_name" value="{{ old('third_name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="الاسم الثالث">
                    </div>
                    <div>
                        <input type="text" name="fourth_name" id="fourth_name" value="{{ old('fourth_name') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="الاسم الرابع">
                    </div>
                </div>
                @error('first_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2 text-left">المحافظة <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <select name="governorate" id="governorate" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left sr-only">المخفر</label>
                        <select name="station" id="station" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">اختر المخفر</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->name }}" {{ old('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left sr-only">المنافذ</label>
                        <select name="ports" id="ports" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">اختر المنفذ</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->name }}" {{ old('ports') == $port->name ? 'selected' : '' }}>{{ $port->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-xs text-gray-500 mt-1">
                    <span></span>
                    <span class="text-center">المخفر</span>
                    <span class="text-center">المنافذ</span>
                </div>
            </div>

            <div class="mb-6">
                <label for="round_date" class="block text-sm font-medium text-gray-700 mb-2 text-left">تاريخ الجولة <span class="text-red-500">*</span></label>
                <input type="date" name="round_date" id="round_date" value="{{ old('round_date', date('Y-m-d')) }}" required
                    class="w-full md:w-1/2 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('round_date') border-red-500 @enderror">
                @error('round_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2 text-left">الملاحظات المدونة</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-lg font-medium transition">
                    حفظ
                </button>
                <button type="reset" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2.5 rounded-lg font-medium transition">
                    مسح
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">السجلات الأخيرة</h2>
            <span class="text-sm text-gray-500">عدد السجلات: {{ \App\Models\Record::count() }}</span>
        </div>
        
        @php
            $recentRecords = \App\Models\Record::latest()->take(5)->get();
        @endphp
        
        @if($recentRecords->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">رقم الصادر</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">الاسم</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">الرتبة</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">المحافظة</th>
                            <th class="px-4 py-3 text-right font-medium text-gray-600">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $record->record_number }}</td>
                            <td class="px-4 py-3">{{ $record->full_name }}</td>
                            <td class="px-4 py-3">{{ $record->rank ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $record->governorate ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $record->round_date?->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500">لا توجد سجلات</p>
                <p class="text-gray-400 text-sm">قم بإضافة سجلات جديدة</p>
            </div>
        @endif
    </div>
</div>
@endsection
