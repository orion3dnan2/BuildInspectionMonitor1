@extends('layouts.app')

@section('title', 'إدخال البيانات - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600">إدخال البيانات</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">إدخال البيانات</h1>
        <p class="text-gray-500">إضافة وتعديل البلاغات والقيود</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
        <form action="{{ route('records.store') }}" method="POST" id="recordForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الصادر <span class="text-red-500">*</span></label>
                    <input type="text" name="record_number" value="{{ old('record_number') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('record_number') border-red-500 @enderror"
                           placeholder="أدخل رقم الصادر">
                    @error('record_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الرقم العسكري</label>
                    <input type="text" name="military_id" value="{{ old('military_id') }}" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('military_id') border-red-500 @enderror"
                           placeholder="أدخل الرقم العسكري">
                    @error('military_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الرتبة <span class="text-red-500">*</span></label>
                    <select name="rank" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white @error('rank') border-red-500 @enderror">
                        <option value="">اختر الرتبة</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ old('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                        @endforeach
                    </select>
                    @error('rank')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الإجراء</label>
                    <input type="text" name="action_type" value="{{ old('action_type') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="أدخل نوع الإجراء">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الرباعي <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-4 gap-4">
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror"
                           placeholder="الاسم الأول">
                    <input type="text" name="second_name" value="{{ old('second_name') }}" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('second_name') border-red-500 @enderror"
                           placeholder="الاسم الثاني">
                    <input type="text" name="third_name" value="{{ old('third_name') }}" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('third_name') border-red-500 @enderror"
                           placeholder="الاسم الثالث">
                    <input type="text" name="fourth_name" value="{{ old('fourth_name') }}" 
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('fourth_name') border-red-500 @enderror"
                           placeholder="الاسم الرابع">
                </div>
                @error('first_name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المحافظة <span class="text-red-500">*</span></label>
                    <select name="governorate" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white @error('governorate') border-red-500 @enderror">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $gov)
                            <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                        @endforeach
                    </select>
                    @error('governorate')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المخفر</label>
                    <select name="station" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">اختر المخفر</option>
                        @foreach($stations as $station)
                            <option value="{{ $station->name }}" {{ old('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المنفذ</label>
                    <select name="ports" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">اختر المنفذ</option>
                        @foreach($ports as $port)
                            <option value="{{ $port->name }}" {{ old('ports') == $port->name ? 'selected' : '' }}>{{ $port->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الجولة <span class="text-red-500">*</span></label>
                    <input type="date" name="round_date" value="{{ old('round_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('round_date') border-red-500 @enderror">
                    @error('round_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الملاحظات</label>
                    <textarea name="notes" rows="1" 
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="أدخل ملاحظات إضافية">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-medium rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    حفظ
                </button>
                <button type="reset" class="flex items-center gap-2 px-8 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    مسح
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-gray-800">السجلات الأخيرة</h2>
            <span class="text-sm text-gray-500">عدد السجلات: {{ \App\Models\Record::count() }}</span>
        </div>
        
        @php
            $recentRecords = \App\Models\Record::latest()->take(5)->get();
        @endphp
        
        @if($recentRecords->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">رقم الصادر</th>
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الرقم العسكري</th>
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الاسم</th>
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الرتبة</th>
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">المحافظة</th>
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">التاريخ</th>
                        <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentRecords as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm text-gray-800">{{ $record->record_number }}</td>
                        <td class="py-4 px-4 text-sm text-gray-800">{{ $record->military_id }}</td>
                        <td class="py-4 px-4 text-sm text-gray-800">{{ $record->full_name }}</td>
                        <td class="py-4 px-4 text-sm text-gray-800">{{ $record->rank ?? '-' }}</td>
                        <td class="py-4 px-4 text-sm text-gray-800">{{ $record->governorate ?? '-' }}</td>
                        <td class="py-4 px-4 text-sm text-gray-500">{{ $record->round_date?->format('Y/m/d') ?? '-' }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('records.edit', $record) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('records.show', $record) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p>لا توجد سجلات حتى الآن</p>
            <p class="text-gray-400 text-sm mt-1">قم بإضافة سجلات جديدة</p>
        </div>
        @endif
    </div>
</div>
@endsection
