@extends('layouts.app')

@section('title', 'إدخال البيانات - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-6">
    <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-3">
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للصفحة الرئيسية
    </a>
    <h1 class="text-xl font-bold text-slate-800">إدخال البيانات</h1>
    <p class="text-slate-500 text-sm">إضافة وتعديل البلاغات والقيود</p>
</div>

@can('create', App\Models\Record::class)
<div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
    <h2 class="text-lg font-bold text-red-700 text-center mb-6">إضافة سجل جديد</h2>
    
    <form action="{{ route('records.store') }}" method="POST" id="recordForm">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="record_number" class="block text-sm text-slate-600 mb-1 text-right">رقم الصادر <span class="text-red-500">*</span></label>
                <input type="text" name="record_number" id="record_number" value="{{ old('record_number') }}" required
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 @error('record_number') border-red-400 @enderror">
                @error('record_number')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="military_id" class="block text-sm text-slate-600 mb-1 text-right">الرقم العسكري</label>
                <input type="text" name="military_id" id="military_id" value="{{ old('military_id') }}"
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400">
            </div>

            <div>
                <label for="rank" class="block text-sm text-slate-600 mb-1 text-right">الرتبة <span class="text-red-500">*</span></label>
                <select name="rank" id="rank" required class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white">
                    <option value="">اختر الرتبة</option>
                    @foreach($ranks ?? [] as $rank)
                        <option value="{{ $rank }}" {{ old('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm text-slate-600 mb-1 text-right">الاسم <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-4 gap-3">
                <div>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required placeholder="الاسم الأول"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-center @error('first_name') border-red-400 @enderror">
                </div>
                <div>
                    <input type="text" name="second_name" id="second_name" value="{{ old('second_name') }}" placeholder="الاسم الثاني"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-center">
                </div>
                <div>
                    <input type="text" name="third_name" id="third_name" value="{{ old('third_name') }}" placeholder="الاسم الثالث"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-center">
                </div>
                <div>
                    <input type="text" name="fourth_name" id="fourth_name" value="{{ old('fourth_name') }}" placeholder="الاسم الرابع"
                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-center">
                </div>
            </div>
            @error('first_name')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
            <div>
                <label for="action_type" class="block text-sm text-slate-600 mb-1 text-right">نوع الإجراء</label>
                <input type="text" name="action_type" id="action_type" value="{{ old('action_type') }}"
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400">
            </div>

            <div>
                <label for="governorate" class="block text-sm text-slate-600 mb-1 text-right">المحافظة</label>
                <select name="governorate" id="governorate" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white">
                    <option value="">اختر المحافظة</option>
                    @foreach($governorates ?? [] as $gov)
                        <option value="{{ $gov }}" {{ old('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-600 mb-1 text-right">نوع الجهة</label>
                <div class="flex items-center gap-4 py-2">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="location_type" value="station" class="form-radio text-sky-500" checked onchange="toggleLocationField()">
                        <span class="mr-2 text-sm text-slate-600">مخفر</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="location_type" value="port" class="form-radio text-sky-500" onchange="toggleLocationField()">
                        <span class="mr-2 text-sm text-slate-600">منفذ</span>
                    </label>
                </div>
            </div>

            <div id="station_field">
                <label for="station" class="block text-sm text-slate-600 mb-1 text-right">المخفر</label>
                <select name="station" id="station" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white">
                    <option value="">اختر المخفر</option>
                    @foreach($stations ?? [] as $station)
                        <option value="{{ $station->name }}" {{ old('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="port_field" style="display: none;">
                <label for="ports" class="block text-sm text-slate-600 mb-1 text-right">المنفذ</label>
                <select name="ports" id="ports" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400 bg-white">
                    <option value="">اختر المنفذ</option>
                    @foreach($ports ?? [] as $port)
                        <option value="{{ $port->name }}" {{ old('ports') == $port->name ? 'selected' : '' }}>{{ $port->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="round_date" class="block text-sm text-slate-600 mb-1 text-right">تاريخ الجولة <span class="text-red-500">*</span></label>
                <input type="date" name="round_date" id="round_date" value="{{ old('round_date', date('Y-m-d')) }}" required
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400">
            </div>
        </div>

        <div class="mb-5">
            <label for="notes" class="block text-sm text-slate-600 mb-1 text-right">الملاحظات المدونة</label>
            <textarea name="notes" id="notes" rows="3"
                class="w-full px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-6 py-2 rounded-md font-bold transition text-sm">
                حفظ
            </button>
            <button type="reset" class="bg-orange-400 hover:bg-orange-500 text-white px-6 py-2 rounded-md font-bold transition text-sm">
                مسح
            </button>
        </div>
    </form>
</div>
@endcan

<div class="bg-white rounded-xl border border-slate-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold text-slate-700">السجلات الأخيرة</h2>
        <span class="text-sm text-slate-400">عدد السجلات: {{ $records->total() ?? 0 }}</span>
    </div>
    
    @if($records->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">رقم الصادر</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الرقم العسكري</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الاسم</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الرتبة</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">المحافظة</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">تاريخ الجولة</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($records as $record)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-700 font-medium">{{ $record->record_number }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $record->military_id ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $record->full_name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $record->rank ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $record->governorate ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $record->round_date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('records.show', $record) }}" class="text-sky-600 hover:text-sky-800 text-xs font-medium">عرض</a>
                                @can('update', $record)
                                <a href="{{ route('records.edit', $record) }}" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">تعديل</a>
                                @endcan
                                @can('delete', $record)
                                <form action="{{ route('records.destroy', $record) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">حذف</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="p-4 border-t border-slate-200 mt-4">
            {{ $records->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-12">
            <p class="text-slate-600 font-medium">لا توجد سجلات</p>
            <p class="text-slate-400 text-sm mt-1">قم بإضافة سجلات جديدة</p>
        </div>
    @endif
</div>

<script>
function toggleLocationField() {
    const locationType = document.querySelector('input[name="location_type"]:checked').value;
    const stationField = document.getElementById('station_field');
    const portField = document.getElementById('port_field');
    
    if (locationType === 'station') {
        stationField.style.display = 'block';
        portField.style.display = 'none';
        document.getElementById('ports').value = '';
    } else {
        stationField.style.display = 'none';
        portField.style.display = 'block';
        document.getElementById('station').value = '';
    }
}
</script>
@endsection
