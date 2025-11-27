@extends('layouts.app')

@section('title', 'الاستعلام والبحث - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة للصفحة الرئيسية
        </a>
        <h1 class="text-xl font-bold text-slate-700">الاستعلام والبحث</h1>
        <p class="text-slate-400 text-sm">البحث عن البلاغات والقيود المسجلة</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <h2 class="text-base font-bold text-slate-700 text-center mb-6">البحث والتصفية</h2>
        
        <form action="{{ route('search.index') }}" method="GET">
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-600 mb-2 text-left">البحث السريع</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2.5 pr-10 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 text-sm"
                        placeholder="ابحث برقم السجل، الاسم، رقم الصادر، أو الرقم العسكري...">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div x-data="{ open: false }" class="mb-5">
                <button type="button" @click="open = !open" class="flex items-center gap-2 text-slate-500 hover:text-slate-700 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span class="text-sm font-medium">الفلاتر المتقدمة</span>
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-transition class="border-t border-slate-200 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">المحافظة</label>
                            <select name="governorate" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 bg-white text-sm">
                                <option value="">الكل</option>
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov }}" {{ request('governorate') == $gov ? 'selected' : '' }}>{{ $gov }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">الرتبة</label>
                            <select name="rank" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 bg-white text-sm">
                                <option value="">الكل</option>
                                @foreach($ranks as $rank)
                                    <option value="{{ $rank }}" {{ request('rank') == $rank ? 'selected' : '' }}>{{ $rank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">المخفر</label>
                            <select name="station" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 bg-white text-sm">
                                <option value="">الكل</option>
                                @foreach($stations as $station)
                                    <option value="{{ $station->name }}" {{ request('station') == $station->name ? 'selected' : '' }}>{{ $station->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">نوع الإجراء</label>
                            <select name="action_type" class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 bg-white text-sm">
                                <option value="">الكل</option>
                                @foreach($actionTypes ?? [] as $type)
                                    <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">من تاريخ</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">إلى تاريخ</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-200 focus:border-sky-400 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded-lg font-medium transition text-sm">
                    بحث
                </button>
                <a href="{{ route('search.index') }}" class="text-slate-500 hover:text-slate-700 text-sm">مسح الفلاتر</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-slate-700">نتائج البحث</h2>
            <span class="text-sm text-slate-400">عدد النتائج: {{ isset($records) ? $records->total() : 0 }} من {{ \App\Models\Record::count() }}</span>
        </div>
        
        @if(isset($searched) && $searched && isset($records) && $records->count() > 0)
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
                            <td class="px-4 py-3 font-medium text-slate-700">{{ $record->record_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->military_id }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->full_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->rank ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->governorate ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $record->round_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('search.show', $record) }}" class="text-sky-500 hover:text-sky-700 text-sm">عرض</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
            <div class="mt-4 pt-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="w-14 h-14 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-slate-500 font-medium">لا توجد نتائج</p>
                <p class="text-slate-400 text-sm">لا توجد سجلات في النظام حالياً</p>
            </div>
        @endif
    </div>

    <footer class="mt-10 pt-5 border-t border-slate-200 flex justify-between items-center text-xs text-slate-400">
        <span>الإصدار 1.0.0</span>
        <span>جميع الحقوق محفوظة &copy; {{ date('Y') }}</span>
    </footer>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
