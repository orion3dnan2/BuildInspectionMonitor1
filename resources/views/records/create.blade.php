@extends('layouts.app')

@section('title', 'إدخال البيانات - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للصفحة الرئيسية
        </a>
        <h1 class="text-xl font-bold text-slate-700">إدخال البيانات</h1>
        <p class="text-slate-400 text-sm">إضافة وتعديل البلاغات والقيود</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-red-700 text-center mb-8">إضافة سجل جديد</h2>
        
        <form action="{{ route('records.store') }}" method="POST" id="recordForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 mb-5">
                <div>
                    <label for="record_number" class="block text-sm text-slate-600 mb-2 text-right">رقم الصادر <span class="text-red-500">*</span></label>
                    <input type="text" name="record_number" id="record_number" value="{{ old('record_number') }}" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('record_number') border-red-400 @enderror">
                    @error('record_number')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="military_id" class="block text-sm text-slate-600 mb-2 text-right">الرقم العسكري</label>
                    <input type="text" name="military_id" id="military_id" value="{{ old('military_id') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('military_id') border-red-400 @enderror">
                    @error('military_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 mb-5">
                <div>
                    <label for="action_type" class="block text-sm text-slate-600 mb-2 text-right">نوع الإجراء في حقه</label>
                    <input type="text" name="action_type" id="action_type" value="{{ old('action_type') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                </div>

                <div>
                    <label for="rank" class="block text-sm text-slate-600 mb-2 text-right">الرتبة <span class="text-red-500">*</span></label>
                    <select name="rank" id="rank" required class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white text-sm">
                        <option value="">اختر الرتبة</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ old('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm text-slate-600 mb-2 text-right">الاسم <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-4 gap-3">
                    <div>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                            class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm text-center @error('first_name') border-red-400 @enderror"
                            placeholder="الاسم الأول">
                    </div>
                    <div>
                        <input type="text" name="second_name" id="second_name" value="{{ old('second_name') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm text-center"
                            placeholder="الاسم الثاني">
                    </div>
                    <div>
                        <input type="text" name="third_name" id="third_name" value="{{ old('third_name') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm text-center"
                            placeholder="الاسم الثالث">
                    </div>
                    <div>
                        <input type="text" name="fourth_name" id="fourth_name" value="{{ old('fourth_name') }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm text-center"
                            placeholder="الاسم الرابع">
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-3 mt-1">
                    <span class="text-xs text-slate-400 text-center">الاسم الأول</span>
                    <span class="text-xs text-slate-400 text-center">الاسم الثاني</span>
                    <span class="text-xs text-slate-400 text-center">الاسم الثالث</span>
                    <span class="text-xs text-slate-400 text-center">الاسم الرابع</span>
                </div>
                @error('first_name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm text-slate-600 mb-2 text-right">المحافظة <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <select name="governorate" id="governorate" class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white text-sm">
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="station" id="station" class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white text-sm">
                            <option value="">اختر المخفر</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->name }}" {{ old('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="ports" id="ports" class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white text-sm">
                            <option value="">اختر المنفذ</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->name }}" {{ old('ports') == $port->name ? 'selected' : '' }}>{{ $port->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-1">
                    <span class="text-xs text-slate-400 text-center"></span>
                    <span class="text-xs text-slate-400 text-center">المخفر</span>
                    <span class="text-xs text-slate-400 text-center">المنافذ</span>
                </div>
            </div>

            <div class="mb-5">
                <label for="round_date" class="block text-sm text-slate-600 mb-2 text-right">تاريخ الجولة <span class="text-red-500">*</span></label>
                <input type="date" name="round_date" id="round_date" value="{{ old('round_date', date('Y-m-d')) }}" required
                    class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('round_date') border-red-400 @enderror">
                @error('round_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm text-slate-600 mb-2 text-right">الملاحظات المدونة</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm"
                    placeholder="">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-5 py-2 rounded font-medium transition text-sm">
                    حفظ
                </button>
                <button type="reset" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-5 py-2 rounded font-medium transition text-sm">
                    مسح
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-slate-700">السجلات الأخيرة</h2>
            <span class="text-sm text-slate-400">عدد السجلات: {{ \App\Models\Record::count() }}</span>
        </div>
        
        @php
            $recentRecords = \App\Models\Record::latest()->take(5)->get();
        @endphp
        
        @if($recentRecords->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">رقم الصادر</th>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">الاسم</th>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">الرتبة</th>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">المحافظة</th>
                            <th class="px-4 py-3 text-right font-medium text-slate-500">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentRecords as $record)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 text-slate-600">{{ $record->record_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->full_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->rank ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->governorate ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->round_date?->format('Y-m-d') ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-slate-600 font-medium">لا توجد سجلات</p>
                <p class="text-slate-400 text-sm mt-1">قم بإضافة سجلات جديدة</p>
            </div>
        @endif
    </div>
</div>
@endsection
