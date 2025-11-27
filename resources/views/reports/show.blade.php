@extends('layouts.app')

@section('title', 'عرض تقرير - نظام التفتيش والمراقبة')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">تفاصيل التقرير</h1>
        <p class="text-gray-600 mt-1">رقم السجل: {{ $report->record_number }}</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('reports.print', $report) }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            طباعة
        </a>
        @can('update', $report)
        <a href="{{ route('reports.edit', $report) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            تعديل
        </a>
        @endcan
        <a href="{{ route('reports.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            رجوع
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">رقم السجل</label>
            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $report->record_number }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">رقم الصادر</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->outgoing_number ?? '-' }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">اسم الضابط</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->officer_name }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">الرتبة</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->rank ?? '-' }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">اسم المكتب</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->office_name }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">تاريخ التفتيش</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->inspection_date->format('Y-m-d') }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">تم الإنشاء بواسطة</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->creator->name ?? '-' }}</p>
        </div>

        <div class="border-b pb-4">
            <label class="block text-sm font-medium text-gray-500">تاريخ الإنشاء</label>
            <p class="mt-1 text-lg text-gray-900">{{ $report->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    @if($report->notes)
    <div class="mt-6 pt-6 border-t">
        <label class="block text-sm font-medium text-gray-500 mb-2">ملاحظات</label>
        <p class="text-gray-900 whitespace-pre-line">{{ $report->notes }}</p>
    </div>
    @endif
</div>
@endsection
