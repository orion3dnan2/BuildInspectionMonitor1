<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة الرقابة والتفتيش')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover { background-color: rgba(59, 130, 246, 0.1); }
        .sidebar-link.active { background-color: rgba(59, 130, 246, 0.15); border-right: 3px solid #3b82f6; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-base">
    <div class="flex min-h-screen">
        @auth
        <aside class="w-72 bg-white border-l border-slate-200 fixed h-full overflow-y-auto shadow-sm">
            <div class="p-5 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-sky-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="font-bold text-slate-700 text-base">نظام إدارة الرقابة والتفتيش</h1>
                        <p class="text-sm text-slate-400">النظام الإلكتروني الموحد</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('home') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('home') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-base">الرئيسية</span>
                </a>

                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="text-base">لوحة التحكم</span>
                </a>

                @if(auth()->user()->canCreateRecords())
                <a href="{{ route('records.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('records.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-base">إدخال البيانات</span>
                </a>
                @endif

                <a href="{{ route('search.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('search.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-base">البحث والاستعلام</span>
                </a>

                <a href="{{ route('reports.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-base">التقارير والإحصائيات</span>
                </a>

                @if(auth()->user()->canImport())
                <a href="{{ route('import.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('import.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span class="text-base">الاستيراد</span>
                </a>
                @endif

                @if(auth()->user()->canManageSettings())
                <a href="{{ route('settings.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-lg text-slate-600 {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-base">الإعدادات</span>
                </a>
                @endif
            </nav>

            <div class="absolute bottom-0 left-0 right-0 p-5 border-t border-slate-100 bg-slate-50">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-11 h-11 bg-sky-100 rounded-full flex items-center justify-center">
                        <span class="text-base font-bold text-sky-600">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-base text-slate-700">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-slate-400">{{ auth()->user()->role_name }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-600 rounded-lg transition text-base">
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
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-base">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-base">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
