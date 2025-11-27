<!DOCTYPE html>
<html lang="ar" dir="rtl" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة الرقابة والتفتيش')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#E0E1DD',
                            100: '#d1d5db',
                            200: '#9ca3af',
                            300: '#6b7280',
                            400: '#415A77',
                            500: '#1B263B',
                            600: '#0D1B2A',
                            700: '#0a1521',
                            800: '#070f17',
                            900: '#040a0e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover { background-color: rgba(59, 130, 246, 0.1); }
        .sidebar-link.active { background-color: rgba(59, 130, 246, 0.15); border-right: 3px solid #3b82f6; }
        .dark .sidebar-link:hover { background-color: rgba(59, 130, 246, 0.2); }
        .dark .sidebar-link.active { background-color: rgba(59, 130, 246, 0.25); }
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="bg-slate-100 dark:bg-slate-900 text-base transition-colors duration-200">
    <div class="flex min-h-screen">
        @auth
        <aside class="w-72 bg-white dark:bg-slate-800 border-l border-slate-200 dark:border-slate-700 fixed h-full overflow-y-auto shadow-sm">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-sky-100 dark:bg-sky-900 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-slate-700 dark:text-white text-base">نظام إدارة الرقابة والتفتيش</h1>
                        <p class="text-sm text-slate-400 dark:text-slate-500">النظام الإلكتروني الموحد</p>
                    </div>
                </div>
            </div>

            @php
                $isAdminSection = request()->routeIs('admin.*') || request('section') === 'admin';
            @endphp

            <div class="p-4 border-b border-slate-100 dark:border-slate-700">
                <div class="flex rounded-lg bg-slate-100 dark:bg-slate-700 p-1">
                    <a href="{{ route('home') }}" class="flex-1 text-center py-2 text-sm rounded-md transition {{ !$isAdminSection ? 'bg-white dark:bg-slate-600 shadow text-sky-600 dark:text-sky-400 font-medium' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white' }}">
                        نظام الحجب
                    </a>
                    <a href="{{ route('home', ['section' => 'admin']) }}" class="flex-1 text-center py-2 text-sm rounded-md transition {{ $isAdminSection ? 'bg-white dark:bg-slate-600 shadow text-sky-600 dark:text-sky-400 font-medium' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white' }}">
                        النظام الإداري
                    </a>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                @if($isAdminSection)
                    <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-base">الرئيسية</span>
                    </a>

                    <div class="pt-2">
                        <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase">الموارد البشرية</p>
                    </div>

                    <a href="{{ route('admin.departments.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-base">الأقسام</span>
                    </a>

                    <a href="{{ route('admin.employees.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-base">الموظفين</span>
                    </a>

                    <a href="{{ route('admin.attendances.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-base">الحضور والانصراف</span>
                    </a>

                    <a href="{{ route('admin.leave-requests.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.leave-requests.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-base">طلبات الإجازات</span>
                    </a>

                    <div class="pt-2">
                        <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase">المراسلات</p>
                    </div>

                    <a href="{{ route('admin.documents.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.documents.index') || request()->routeIs('admin.documents.create') || request()->routeIs('admin.documents.edit') || request()->routeIs('admin.documents.show') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-base">جميع المستندات</span>
                    </a>

                    <a href="{{ route('admin.documents.inbox') }}" class="sidebar-link flex items-center justify-between px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.documents.inbox') ? 'active' : '' }}">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <span class="text-base">صندوق الوارد</span>
                        </div>
                        <span id="docsInboxBadge" class="hidden bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                    </a>

                    <a href="{{ route('admin.documents.my-documents') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('admin.documents.my-documents') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span class="text-base">مستنداتي</span>
                    </a>

                @else
                    <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('home') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="text-base">الرئيسية</span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="text-base">لوحة التحكم</span>
                    </a>

                    @if(auth()->user()->canCreateRecords())
                    <a href="{{ route('records.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('records.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-base">إدخال البيانات</span>
                    </a>
                    @endif

                    <div class="pt-2">
                        <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase">دفتر القيد</p>
                    </div>

                    <a href="{{ route('books.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('books.index') || request()->routeIs('books.create') || request()->routeIs('books.edit') || request()->routeIs('books.show') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-base">جميع القيود</span>
                    </a>

                    @if(in_array(auth()->user()->role, ['admin', 'supervisor']))
                    <a href="{{ route('books.inbox') }}" class="sidebar-link flex items-center justify-between px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('books.inbox') ? 'active' : '' }}">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <span class="text-base">صندوق الوارد</span>
                        </div>
                        <span id="booksInboxBadge" class="hidden bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                    </a>
                    @endif

                    <a href="{{ route('books.my-books') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('books.my-books') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        <span class="text-base">قيوداتي</span>
                    </a>

                    <div class="pt-2">
                        <p class="px-4 py-2 text-xs font-medium text-slate-400 uppercase">البحث والتقارير</p>
                    </div>

                    <a href="{{ route('search.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('search.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="text-base">البحث والاستعلام</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="text-base">التقارير والإحصائيات</span>
                    </a>

                    @if(auth()->user()->canImport())
                    <a href="{{ route('import.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('import.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        <span class="text-base">الاستيراد</span>
                    </a>
                    @endif

                    @if(auth()->user()->canManageSettings())
                    <a href="{{ route('settings.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 dark:text-slate-300 {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-base">الإعدادات</span>
                    </a>
                    @endif
                @endif
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-5 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sky-100 dark:bg-sky-900 rounded-full flex items-center justify-center">
                            <span class="text-sm font-bold text-sky-600 dark:text-sky-400">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-slate-700 dark:text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">{{ auth()->user()->role_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative" id="notificationContainer">
                            <button id="notificationBtn" type="button" class="p-2 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span id="notificationBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                            </button>
                            <div id="notificationDropdown" class="hidden absolute left-0 bottom-full mb-2 w-80 bg-white dark:bg-slate-800 rounded-lg shadow-xl border border-slate-200 dark:border-slate-700 z-50 max-h-96 overflow-hidden">
                                <div class="p-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                    <h3 class="font-bold text-slate-700 dark:text-white text-sm">الإشعارات</h3>
                                    <button id="markAllRead" class="text-xs text-sky-500 hover:text-sky-600">تحديد الكل كمقروء</button>
                                </div>
                                <div id="notificationList" class="max-h-72 overflow-y-auto">
                                    <div class="p-4 text-center text-slate-400 text-sm">جاري التحميل...</div>
                                </div>
                            </div>
                        </div>
                        <button id="darkModeToggle" type="button" class="p-2 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-lg transition text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <main class="@auth mr-72 @endauth flex-1 p-8">
            <div class="max-w-screen-2xl mx-auto">
                @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-lg text-base">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-lg text-base">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.getElementById('darkModeToggle')?.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        });

        // Notification System
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');
        const markAllReadBtn = document.getElementById('markAllRead');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        let notificationsData = [];

        function updateBadge(count) {
            if (count > 0) {
                notificationBadge.textContent = count > 99 ? '99+' : count;
                notificationBadge.classList.remove('hidden');
                notificationBadge.classList.add('flex');
            } else {
                notificationBadge.classList.add('hidden');
                notificationBadge.classList.remove('flex');
            }
        }

        function renderNotifications(notifications) {
            if (notifications.length === 0) {
                notificationList.innerHTML = '<div class="p-4 text-center text-slate-400 text-sm">لا توجد إشعارات</div>';
                return;
            }

            notificationList.innerHTML = notifications.map(notification => `
                <a href="${notification.url || '#'}" 
                   class="block p-3 border-b border-slate-100 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition ${notification.read_at ? 'opacity-60' : ''}"
                   data-notification-id="${notification.id}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center ${notification.color}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${notification.icon}"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 dark:text-white truncate">${notification.title}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">${notification.message}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">${notification.created_at}</p>
                        </div>
                        ${!notification.read_at ? '<span class="w-2 h-2 bg-sky-500 rounded-full flex-shrink-0"></span>' : ''}
                    </div>
                </a>
            `).join('');

            // Add click handlers to mark as read
            notificationList.querySelectorAll('[data-notification-id]').forEach(el => {
                el.addEventListener('click', function(e) {
                    const id = this.dataset.notificationId;
                    markAsRead(id);
                });
            });
        }

        async function loadNotifications() {
            try {
                const response = await fetch('/notifications');
                const data = await response.json();
                notificationsData = data.notifications;
                updateBadge(data.unread_count);
                renderNotifications(notificationsData);
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        async function loadCounts() {
            try {
                const response = await fetch('/notifications/counts');
                const data = await response.json();
                updateBadge(data.unread_notifications);
                
                // Update inbox badges
                const booksInboxBadge = document.getElementById('booksInboxBadge');
                const docsInboxBadge = document.getElementById('docsInboxBadge');
                
                if (booksInboxBadge) {
                    if (data.pending_books > 0) {
                        booksInboxBadge.textContent = data.pending_books;
                        booksInboxBadge.classList.remove('hidden');
                    } else {
                        booksInboxBadge.classList.add('hidden');
                    }
                }
                
                if (docsInboxBadge) {
                    if (data.pending_documents > 0) {
                        docsInboxBadge.textContent = data.pending_documents;
                        docsInboxBadge.classList.remove('hidden');
                    } else {
                        docsInboxBadge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading counts:', error);
            }
        }

        async function markAsRead(id) {
            try {
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                loadCounts();
            } catch (error) {
                console.error('Error marking as read:', error);
            }
        }

        async function markAllAsRead() {
            try {
                await fetch('/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                loadNotifications();
                loadCounts();
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        }

        notificationBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = notificationDropdown.classList.contains('hidden');
            if (isHidden) {
                loadNotifications();
                notificationDropdown.classList.remove('hidden');
            } else {
                notificationDropdown.classList.add('hidden');
            }
        });

        markAllReadBtn?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            markAllAsRead();
        });

        document.addEventListener('click', function(e) {
            if (!notificationDropdown?.contains(e.target) && !notificationBtn?.contains(e.target)) {
                notificationDropdown?.classList.add('hidden');
            }
        });

        // Load initial counts
        loadCounts();
        
        // Refresh counts every 30 seconds
        setInterval(loadCounts, 30000);
    </script>
    @stack('scripts')
</body>
</html>
