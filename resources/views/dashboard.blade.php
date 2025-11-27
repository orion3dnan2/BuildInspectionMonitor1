@extends('layouts.app')

@section('title', 'الرئيسية - نظام التفتيش والمراقبة')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">لوحة التحكم</h1>
    <p class="text-gray-600 mt-1">مرحباً بك في نظام التفتيش والمراقبة</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">إجمالي التقارير</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalReports }}</h3>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">تقارير اليوم</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $todayReports }}</h3>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-r-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">عدد المفتشين</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalInspectors }}</h3>
            </div>
            <div class="bg-purple-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">آخر التقارير</h2>
        <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض الكل</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم السجل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الضابط</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المكتب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التفتيش</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">بواسطة</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($recentReports as $report)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->record_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->officer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->office_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->inspection_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->creator->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا توجد تقارير حتى الآن</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
