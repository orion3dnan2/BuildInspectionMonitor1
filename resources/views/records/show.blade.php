@extends('layouts.app')

@section('title', 'عرض السجل - نظام الرقابة والتفتيش')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">تفاصيل السجل</h1>
        <p class="text-gray-600">رقم التتبع: <span class="text-sky-600 font-mono">{{ $record->tracking_number ?? '-' }}</span></p>
    </div>
    <div class="flex gap-2">
        @can('update', $record)
        <a href="{{ route('records.edit', $record) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">تعديل</a>
        @endcan
        <a href="{{ route('records.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">رجوع</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <p class="text-sm text-gray-500">رقم التتبع</p>
            <p class="font-medium text-sky-600 font-mono">{{ $record->tracking_number ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">رقم الصادر</p>
            <p class="font-medium text-gray-800">{{ $record->record_number }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">الرقم العسكري</p>
            <p class="font-medium text-gray-800">{{ $record->military_id ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">تاريخ الجولة</p>
            <p class="font-medium text-gray-800">{{ $record->round_date->format('Y-m-d') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">الاسم الكامل</p>
            <p class="font-medium text-gray-800">{{ $record->full_name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">الرتبة</p>
            <p class="font-medium text-gray-800">{{ $record->rank ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">المحافظة</p>
            <p class="font-medium text-gray-800">{{ $record->governorate ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">المخفر</p>
            <p class="font-medium text-gray-800">{{ $record->station ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">نوع الإجراء</p>
            <p class="font-medium text-gray-800">{{ $record->action_type ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">المنافذ</p>
            <p class="font-medium text-gray-800">{{ $record->ports ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">تاريخ الإنشاء</p>
            <p class="font-medium text-gray-800">{{ $record->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">بواسطة</p>
            <p class="font-medium text-gray-800">{{ $record->creator->name ?? '-' }}</p>
        </div>
    </div>

    @if($record->notes)
    <div class="mt-6 pt-6 border-t border-gray-200">
        <p class="text-sm text-gray-500 mb-2">الملاحظات</p>
        <p class="text-gray-800">{{ $record->notes }}</p>
    </div>
    @endif
</div>
@endsection
