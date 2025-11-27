@extends('layouts.app')

@section('title', 'الاستعلام والبحث - نظام الرقابة والتفتيش')

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
            <span class="text-gray-600">البحث والاستعلام</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">الاستعلام والبحث</h1>
        <p class="text-gray-500">البحث عن البلاغات والقيود المسجلة</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
        <form action="{{ route('search.index') }}" method="GET">
            <div class="mb-6">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-6 py-4 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                        placeholder="بحث رقم السجل، الاسم، رقم الصادر، أو الرقم العسكري...">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <button type="button" onclick="toggleFilters()" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>فلاتر متقدمة</span>
                    <svg id="filter-arrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div id="filters-panel" class="hidden mt-6 pt-6 border-t border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المحافظة</label>
                            <select name="governorate" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">الكل</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov }}" {{ request('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">الرتبة</label>
                            <select name="rank" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">الكل</option>
                                @foreach($ranks as $rank)
                                    <option value="{{ $rank }}" {{ request('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">المخفر</label>
                            <select name="station" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">الكل</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->name }}" {{ request('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع الإجراء</label>
                            <select name="action_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">الكل</option>
                                @foreach($actionTypes ?? [] as $type)
                                    <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    بحث
                </button>
                <a href="{{ route('search.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">مسح الفلاتر</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-gray-800">نتائج البحث</h2>
            <span class="text-sm text-gray-500">عدد النتائج: {{ isset($records) ? $records->total() : 0 }}</span>
        </div>
        
        @if(isset($searched) && $searched && isset($records) && $records->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">رقم الصادر</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الرقم العسكري</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الاسم</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الرتبة</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">المحافظة</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">تاريخ الجولة</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-4 text-sm font-medium text-gray-800">{{ $record->record_number }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $record->military_id }}</td>
                            <td class="py-4 px-4 text-sm text-gray-800">{{ $record->full_name }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $record->rank ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $record->governorate ?? '-' }}</td>
                            <td class="py-4 px-4 text-sm text-gray-500">{{ $record->round_date?->format('Y/m/d') ?? '-' }}</td>
                            <td class="py-4 px-4">
                                <a href="{{ route('search.show', $record) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    عرض
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
            <div class="mt-6 pt-6 border-t border-gray-100">
                {{ $records->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-16">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray-500 font-medium text-lg">لا توجد نتائج</p>
                <p class="text-gray-400 text-sm mt-1">قم بإدخال كلمة البحث أو استخدم الفلاتر للعثور على السجلات</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleFilters() {
    const panel = document.getElementById('filters-panel');
    const arrow = document.getElementById('filter-arrow');
    panel.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}
</script>
@endpush
@endsection
