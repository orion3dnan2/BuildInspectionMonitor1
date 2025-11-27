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
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
        <div>
            <h2 class="text-lg font-bold text-slate-700">جدول البيانات</h2>
            <span class="text-sm text-slate-400">إجمالي السجلات: {{ $records->total() ?? 0 }}</span>
        </div>
        
        <form action="{{ route('records.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="بحث برقم التتبع أو الاسم..."
                class="flex-1 md:w-64 px-3 py-2 text-sm border border-slate-300 rounded-md focus:ring-1 focus:ring-sky-400 focus:border-sky-400">
            <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-md text-sm transition">
                بحث
            </button>
            @if(request('search'))
            <a href="{{ route('records.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-md text-sm transition">
                إلغاء
            </a>
            @endif
        </form>
    </div>
    
    @if($records->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">رقم التتبع</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">رقم الصادر</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">الرقم العسكري</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">الاسم</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">الرتبة</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">المحافظة</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">المخفر/المنفذ</th>
                        <th class="px-3 py-3 text-right font-semibold text-slate-600">تاريخ الجولة</th>
                        <th class="px-3 py-3 text-center font-semibold text-slate-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($records as $record)
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-3 text-sky-600 font-mono text-xs">{{ $record->tracking_number ?? '-' }}</td>
                        <td class="px-3 py-3 text-slate-700 font-medium">{{ $record->record_number }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->military_id ?? '-' }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->full_name }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->rank ?? '-' }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->governorate ?? '-' }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->station ?? $record->ports ?? '-' }}</td>
                        <td class="px-3 py-3 text-slate-600">{{ $record->round_date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="px-3 py-3">
                            <div class="flex items-center justify-center gap-2">
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
        <div class="flex items-center justify-between border-t border-slate-200 pt-4 mt-4">
            <div class="text-sm text-slate-500">
                عرض {{ $records->firstItem() }} - {{ $records->lastItem() }} من {{ $records->total() }} سجل
            </div>
            <div class="flex items-center gap-1">
                @if($records->onFirstPage())
                    <span class="px-3 py-1 text-sm text-slate-400 bg-slate-100 rounded cursor-not-allowed">السابق</span>
                @else
                    <a href="{{ $records->previousPageUrl() }}" class="px-3 py-1 text-sm text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">السابق</a>
                @endif
                
                @foreach($records->getUrlRange(max(1, $records->currentPage() - 2), min($records->lastPage(), $records->currentPage() + 2)) as $page => $url)
                    @if($page == $records->currentPage())
                        <span class="px-3 py-1 text-sm text-white bg-sky-500 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-sm text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($records->hasMorePages())
                    <a href="{{ $records->nextPageUrl() }}" class="px-3 py-1 text-sm text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">التالي</a>
                @else
                    <span class="px-3 py-1 text-sm text-slate-400 bg-slate-100 rounded cursor-not-allowed">التالي</span>
                @endif
            </div>
        </div>
        @endif
    @else
        <div class="text-center py-12">
            @if(request('search'))
                <p class="text-slate-600 font-medium">لا توجد نتائج للبحث "{{ request('search') }}"</p>
                <a href="{{ route('records.index') }}" class="text-sky-600 hover:text-sky-800 text-sm mt-2 inline-block">عرض كل السجلات</a>
            @else
                <p class="text-slate-600 font-medium">لا توجد سجلات</p>
                <p class="text-slate-400 text-sm mt-1">قم بإضافة سجلات جديدة</p>
            @endif
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
