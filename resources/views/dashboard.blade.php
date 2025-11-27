@extends('layouts.app')

@section('title', 'لوحة التحكم - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-8">
    <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 mb-4">
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للصفحة الرئيسية
    </a>
    <h1 class="text-2xl font-bold text-slate-700">لوحة التحكم</h1>
    <p class="text-slate-400">إحصائيات وملخص النظام</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-base">إجمالي السجلات</p>
                <h3 class="text-4xl font-bold text-slate-700 mt-2">{{ $totalRecords }}</h3>
            </div>
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-base">سجلات اليوم</p>
                <h3 class="text-4xl font-bold text-slate-700 mt-2">{{ $todayRecords }}</h3>
            </div>
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-base">سجلات الشهر</p>
                <h3 class="text-4xl font-bold text-slate-700 mt-2">{{ $monthRecords }}</h3>
            </div>
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-base">سجلات السنة</p>
                <h3 class="text-4xl font-bold text-slate-700 mt-2">{{ $yearRecords }}</h3>
            </div>
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    @if(auth()->user()->canCreateRecords())
    <a href="{{ route('records.create') }}" class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-5 hover:border-sky-300 hover:bg-sky-50 transition group">
        <div class="w-16 h-16 bg-slate-100 group-hover:bg-sky-100 rounded-xl flex items-center justify-center transition">
            <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        <span class="text-lg font-medium text-slate-700">إدخال بيانات جديدة</span>
    </a>
    @endif

    <a href="{{ route('search.index') }}" class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-5 hover:border-sky-300 hover:bg-sky-50 transition group">
        <div class="w-16 h-16 bg-slate-100 group-hover:bg-sky-100 rounded-xl flex items-center justify-center transition">
            <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <span class="text-lg font-medium text-slate-700">البحث والاستعلام</span>
    </a>

    <a href="{{ route('reports.index') }}" class="bg-white rounded-xl border border-slate-200 p-6 flex items-center gap-5 hover:border-sky-300 hover:bg-sky-50 transition group">
        <div class="w-16 h-16 bg-slate-100 group-hover:bg-sky-100 rounded-xl flex items-center justify-center transition">
            <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <span class="text-lg font-medium text-slate-700">التقارير والإحصائيات</span>
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200">
    <div class="p-6 border-b border-slate-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-700">آخر السجلات</h2>
        <a href="{{ route('records.index') }}" class="text-sky-500 hover:text-sky-700">عرض الكل</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-right font-medium text-slate-500">رقم الصادر</th>
                    <th class="px-6 py-4 text-right font-medium text-slate-500">الاسم</th>
                    <th class="px-6 py-4 text-right font-medium text-slate-500">المحافظة</th>
                    <th class="px-6 py-4 text-right font-medium text-slate-500">تاريخ الجولة</th>
                    <th class="px-6 py-4 text-right font-medium text-slate-500">بواسطة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentRecords as $record)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-medium text-slate-700">{{ $record->record_number }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $record->full_name }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $record->governorate ?? '-' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $record->round_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $record->creator->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">لا توجد سجلات حتى الآن</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
