@extends('layouts.app')

@section('title', 'التقارير والإحصائيات - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">التقارير والإحصائيات</h1>
    <p class="text-gray-600">عرض تقارير وإحصائيات السجلات</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-blue-500">
        <p class="text-gray-500 text-sm">إجمالي السجلات</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-green-500">
        <p class="text-gray-500 text-sm">سجلات اليوم</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['today'] }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-yellow-500">
        <p class="text-gray-500 text-sm">سجلات الشهر</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['month'] }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-purple-500">
        <p class="text-gray-500 text-sm">سجلات السنة</p>
        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['year'] }}</h3>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-800 mb-4">فلاتر التقارير</h3>
    <form action="{{ route('reports.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الفترة الزمنية</label>
                <select name="period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">الكل</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>اليوم</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>هذا الشهر</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>هذه السنة</option>
                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>مخصص</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المحافظة</label>
                <select name="governorate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">الكل</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov }}" {{ request('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المخفر</label>
                <select name="station" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">الكل</option>
                    @foreach($stations as $station)
                        <option value="{{ $station->name }}" {{ request('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الرتبة</label>
                <select name="rank" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">الكل</option>
                    @foreach($ranks as $rank)
                        <option value="{{ $rank }}" {{ request('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الإجراء</label>
                <select name="action_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">الكل</option>
                    @foreach($actionTypes as $type)
                        <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البحث بالاسم</label>
                <input type="text" name="name" value="{{ request('name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="أدخل الاسم">
            </div>
        </div>
        <div class="mt-4 flex items-center gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                تطبيق الفلاتر
            </button>
            <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-800">مسح الفلاتر</a>
            <a href="{{ route('reports.print', request()->all()) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                طباعة التقرير
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-800">السجلات ({{ $records->total() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الصادر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرتبة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المحافظة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المخفر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع الإجراء</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الجولة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($records as $record)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record->record_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->full_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->rank ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->governorate ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->station ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->action_type ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->round_date->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">لا توجد سجلات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($records->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endsection
