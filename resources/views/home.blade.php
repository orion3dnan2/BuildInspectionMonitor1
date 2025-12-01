@extends('layouts.app')

@section('title', 'الرئيسية - نظام الرقابة والتفتيش')

@section('content')
@php
    $user = auth()->user();
    $canAccessBlockSystem = $user->canAccessBlockSystem();
    $canAccessAdminSystem = $user->canAccessAdminSystem();
    
    // Determine which section to show
    if (request('section') === 'admin' && $canAccessAdminSystem) {
        $isAdminSection = true;
    } elseif (!$canAccessBlockSystem && $canAccessAdminSystem) {
        $isAdminSection = true;
    } else {
        $isAdminSection = false;
    }
@endphp

<div class="text-center mb-10">
    <div class="w-20 h-20 bg-white border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
        <svg class="w-10 h-10 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-slate-700 mb-3">منصة إدارة الرقابة والتفتيش</h1>
    <p class="text-slate-400 text-lg">النظام الإلكتروني الموحد للرقابة الحكومية</p>
</div>

@if($canAccessBlockSystem && $canAccessAdminSystem)
<div class="flex justify-center mb-10">
    <div class="inline-flex bg-white rounded-xl border border-slate-200 p-1.5">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-8 py-3 rounded-lg font-medium transition {{ !$isAdminSection ? 'bg-slate-100 text-slate-700' : 'text-slate-400 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            نظام الحجب
        </a>
        <a href="{{ route('home', ['section' => 'admin']) }}" class="flex items-center gap-3 px-8 py-3 rounded-lg font-medium transition {{ $isAdminSection ? 'bg-slate-100 text-slate-700' : 'text-slate-400 hover:text-slate-600' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            النظام الإداري
        </a>
    </div>
</div>
@elseif($canAccessBlockSystem)
<div class="flex justify-center mb-10">
    <div class="inline-flex bg-white rounded-xl border border-slate-200 p-1.5">
        <span class="flex items-center gap-3 px-8 py-3 rounded-lg font-medium bg-slate-100 text-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            نظام الحجب
        </span>
    </div>
</div>
@elseif($canAccessAdminSystem)
<div class="flex justify-center mb-10">
    <div class="inline-flex bg-white rounded-xl border border-slate-200 p-1.5">
        <span class="flex items-center gap-3 px-8 py-3 rounded-lg font-medium bg-slate-100 text-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            النظام الإداري
        </span>
    </div>
</div>
@endif

@if($canAccessBlockSystem)
<div id="content-blocks" class="{{ $isAdminSection ? 'hidden' : '' }}">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <a href="{{ route('dashboard') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">لوحة التحكم</h3>
            <p class="text-slate-400">الصفحة الرئيسية للنظام</p>
        </a>

        <a href="{{ route('search.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">البحث</h3>
            <p class="text-slate-400">البحث في السجلات</p>
        </a>

        @if(auth()->user()->canAccessDataEntry() || auth()->user()->canCreateRecords())
        <a href="{{ route('records.create') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">إدخال البيانات</h3>
            <p class="text-slate-400">إضافة وتعديل السجلات</p>
        </a>
        @endif

        <a href="{{ route('reports.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">التقارير</h3>
            <p class="text-slate-400">عرض الإحصائيات والتقارير</p>
        </a>

        @if(auth()->user()->canManageSettings())
        <a href="{{ route('settings.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">الإعدادات</h3>
            <p class="text-slate-400">إدارة الإعدادات والبيانات الأساسية</p>
        </a>
        @endif

        @if(auth()->user()->canImport())
        <a href="{{ route('import.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">استيراد</h3>
            <p class="text-slate-400">استيراد البيانات من Excel</p>
        </a>
        @endif
    </div>
</div>
@endif

@if($canAccessAdminSystem)
<div id="content-admin" class="{{ !$isAdminSection ? 'hidden' : '' }}">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @if(auth()->user()->can('employees.view') || auth()->user()->canManageSettings())
        <a href="{{ route('admin.employees.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">إدارة الموظفين</h3>
            <p class="text-slate-400">بيانات الموظفين والموارد البشرية</p>
        </a>
        @endif

        @if(auth()->user()->can('departments.view') || auth()->user()->canManageSettings())
        <a href="{{ route('admin.departments.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">إدارة الأقسام</h3>
            <p class="text-slate-400">الهيكل التنظيمي والإدارات</p>
        </a>
        @endif

        @if(auth()->user()->can('attendances.view') || auth()->user()->canManageSettings())
        <a href="{{ route('admin.attendances.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">سجل الحضور</h3>
            <p class="text-slate-400">تسجيل ومتابعة الحضور والانصراف</p>
        </a>
        @endif

        @if(auth()->user()->can('leave_requests.view') || auth()->user()->canManageSettings())
        <a href="{{ route('admin.leave-requests.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">طلبات الإجازات</h3>
            <p class="text-slate-400">إدارة طلبات الإجازات والموافقات</p>
        </a>
        @endif

        @if(auth()->user()->can('documents.view') || auth()->user()->canManageSettings())
        <a href="{{ route('admin.documents.index') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">المراسلات والكتب</h3>
            <p class="text-slate-400">إنشاء وإدارة المستندات الرسمية</p>
        </a>

        <a href="{{ route('admin.documents.inbox') }}" class="block bg-white rounded-xl hover:shadow-lg transition p-8 text-center group border border-slate-200">
            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-5 group-hover:bg-sky-50 transition">
                <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700 mb-2">صندوق الوارد</h3>
            <p class="text-slate-400">المستندات المحالة للمراجعة والاعتماد</p>
        </a>
        @endif
    </div>
</div>
@endif

<footer class="mt-12 pt-6 border-t border-slate-200 flex justify-between items-center text-slate-400">
    <span>الإصدار 1.0.0</span>
    <span>جميع الحقوق محفوظة &copy; {{ date('Y') }}</span>
</footer>

@endsection
