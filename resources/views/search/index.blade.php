@extends('layouts.app')

@section('title', 'البحث والاستعلام - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">البحث والاستعلام</h1>
    <p class="text-gray-600">ابحث في سجلات الرقابة والتفتيش</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <form action="{{ route('search.index') }}" method="GET">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">البحث السريع</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الصادر</label>
                    <input type="text" name="record_number" value="{{ request('record_number') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="أدخل رقم الصادر">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الرقم العسكري</label>
                    <input type="text" name="military_id" value="{{ request('military_id') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="أدخل الرقم العسكري">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم</label>
                    <input type="text" name="name" value="{{ request('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="أدخل الاسم">
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">فلاتر متقدمة</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">الرتبة</label>
                    <select name="rank" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">الكل</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank }}" {{ request('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                بحث
            </button>
            <a href="{{ route('search.index') }}" class="text-gray-600 hover:text-gray-800">مسح الفلاتر</a>
        </div>
    </form>
</div>

@if($searched)
<div class="bg-white rounded-xl shadow-sm">
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-800">نتائج البحث ({{ $records->total() }} سجل)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الصادر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرقم العسكري</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرتبة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المحافظة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الجولة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عرض</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($records as $record)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record->record_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->military_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->full_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->rank ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->governorate ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $record->round_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('search.show', $record) }}" class="text-blue-600 hover:text-blue-800">عرض التفاصيل</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">لا توجد نتائج</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($records && $records->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $records->links() }}
    </div>
    @endif
</div>
@endif
@endsection
