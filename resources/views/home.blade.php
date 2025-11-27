@extends('layouts.app')

@section('title', 'الرئيسية - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="text-center mb-10">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">منصة إدارة الرقابة والتفتيش</h1>
        <p class="text-gray-500">النظام الإلكتروني الموحد للرقابة الحكومية</p>
    </div>

    <div class="flex justify-center mb-8">
        <div class="inline-flex bg-white rounded-lg shadow-sm border border-gray-200 p-1">
            <button onclick="showTab('blocks')" id="tab-blocks" class="tab-btn flex items-center gap-2 px-6 py-2.5 rounded-md text-sm font-medium transition bg-gray-100 text-gray-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                نظام البلوكات
            </button>
            <button onclick="showTab('admin')" id="tab-admin" class="tab-btn flex items-center gap-2 px-6 py-2.5 rounded-md text-sm font-medium transition text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                نظام إداري
            </button>
        </div>
    </div>

    <div id="content-blocks" class="tab-content">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <a href="{{ route('dashboard') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 text-center group border border-gray-100">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">لوحة التحكم</h3>
                <p class="text-gray-500 text-sm">الصفحة الرئيسية للنظام</p>
            </a>

            <a href="{{ route('search.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 text-center group border border-gray-100">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">البحث</h3>
                <p class="text-gray-500 text-sm">البحث في السجلات</p>
            </a>

            @if(auth()->user()->canCreateRecords())
            <a href="{{ route('records.create') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 text-center group border border-gray-100">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">إدخال البيانات</h3>
                <p class="text-gray-500 text-sm">إضافة وتعديل السجلات</p>
            </a>
            @endif

            <a href="{{ route('reports.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 text-center group border border-gray-100">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">التقارير</h3>
                <p class="text-gray-500 text-sm">عرض الإحصائيات والتقارير</p>
            </a>

            @if(auth()->user()->canManageSettings())
            <a href="{{ route('settings.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 text-center group border border-gray-100">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">الإعدادات</h3>
                <p class="text-gray-500 text-sm">إدارة الإعدادات والبيانات الأساسية</p>
            </a>
            @endif

            @if(auth()->user()->canImport())
            <a href="{{ route('import.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 text-center group border border-gray-100">
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-100 transition">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">استيراد</h3>
                <p class="text-gray-500 text-sm">استيراد البيانات من Excel</p>
            </a>
            @endif
        </div>
    </div>

    <div id="content-admin" class="tab-content hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="block bg-white rounded-xl shadow-sm p-6 text-center border border-gray-100 opacity-60">
                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">إدارة الموارد البشرية</h3>
                <p class="text-gray-500 text-sm">قريباً...</p>
            </div>
        </div>
    </div>

    <footer class="mt-12 pt-6 border-t border-gray-200 flex justify-between items-center text-sm text-gray-500">
        <span>الإصدار 1.0.0</span>
        <span>جميع الحقوق محفوظة &copy; {{ date('Y') }}</span>
    </footer>
</div>

@push('scripts')
<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('bg-gray-100', 'text-gray-800');
        el.classList.add('text-gray-500');
    });
    
    document.getElementById('content-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('bg-gray-100', 'text-gray-800');
    document.getElementById('tab-' + tab).classList.remove('text-gray-500');
}
</script>
@endpush
@endsection
