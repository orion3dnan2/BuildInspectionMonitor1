@extends('layouts.app')

@section('title', 'الرئيسية - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">مرحباً، {{ auth()->user()->name }}</h1>
    <p class="text-gray-600 mt-2">اختر القسم الذي تريد الوصول إليه</p>
</div>

<div class="mb-8">
    <div class="flex border-b border-gray-200">
        <button onclick="showTab('blocks')" id="tab-blocks" class="tab-btn px-6 py-3 font-medium text-blue-600 border-b-2 border-blue-600">
            نظام البلوكات
        </button>
        <button onclick="showTab('admin')" id="tab-admin" class="tab-btn px-6 py-3 font-medium text-gray-500 hover:text-gray-700">
            النظام الإداري
        </button>
    </div>
</div>

<div id="content-blocks" class="tab-content">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('dashboard') }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 border-r-4 border-blue-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">لوحة التحكم</h3>
                    <p class="text-gray-500">عرض الإحصائيات السريعة</p>
                </div>
            </div>
        </a>

        @if(auth()->user()->canCreateRecords())
        <a href="{{ route('records.create') }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 border-r-4 border-green-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">إدخال البيانات</h3>
                    <p class="text-gray-500">إضافة سجلات جديدة</p>
                </div>
            </div>
        </a>
        @endif

        <a href="{{ route('search.index') }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 border-r-4 border-purple-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">البحث</h3>
                    <p class="text-gray-500">البحث والاستعلام</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.index') }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 border-r-4 border-yellow-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">التقارير</h3>
                    <p class="text-gray-500">التقارير والإحصائيات</p>
                </div>
            </div>
        </a>

        @if(auth()->user()->canImport())
        <a href="{{ route('import.index') }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 border-r-4 border-indigo-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">الاستيراد</h3>
                    <p class="text-gray-500">استيراد ملفات Excel</p>
                </div>
            </div>
        </a>
        @endif

        @if(auth()->user()->canManageSettings())
        <a href="{{ route('settings.index') }}" class="block bg-white rounded-xl shadow-md hover:shadow-lg transition p-6 border-r-4 border-gray-500">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">الإعدادات</h3>
                    <p class="text-gray-500">إعدادات النظام</p>
                </div>
            </div>
        </a>
        @endif
    </div>
</div>

<div id="content-admin" class="tab-content hidden">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="block bg-white rounded-xl shadow-md p-6 border-r-4 border-teal-500 opacity-60">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">إدارة الموارد البشرية</h3>
                    <p class="text-gray-500">قريباً...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        el.classList.add('text-gray-500');
    });
    
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    document.getElementById('tab-' + tab).classList.remove('text-gray-500');
}
</script>
@endpush
@endsection
