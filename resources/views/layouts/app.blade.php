<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام التفتيش والمراقبة')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        [dir="rtl"] { direction: rtl; text-align: right; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex min-h-screen">
        @auth
        <aside class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white fixed h-full shadow-xl">
            <div class="p-6 border-b border-blue-700">
                <h1 class="text-xl font-bold">نظام التفتيش والمراقبة</h1>
                <p class="text-blue-200 text-sm mt-1">{{ auth()->user()->name }}</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition {{ request()->routeIs('dashboard') ? 'bg-blue-700 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    الرئيسية
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition {{ request()->routeIs('reports.*') ? 'bg-blue-700 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    التقارير
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition {{ request()->routeIs('users.*') ? 'bg-blue-700 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    المستخدمين
                </a>
                <a href="{{ route('activity-logs.index') }}" class="flex items-center px-6 py-3 text-blue-100 hover:bg-blue-700 transition {{ request()->routeIs('activity-logs.*') ? 'bg-blue-700 border-r-4 border-white' : '' }}">
                    <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    سجل النشاط
                </a>
                @endif
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-blue-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-blue-200 hover:text-white hover:bg-blue-700 rounded transition">
                        <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <main class="@auth mr-64 @endauth flex-1 p-8">
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
