@extends('layouts.app')

@section('title', 'غير مصرح لك')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center">
        <div class="w-32 h-32 mx-auto mb-8 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
            <svg class="w-16 h-16 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-slate-800 dark:text-white mb-4">غير مصرح لك</h1>
        <p class="text-xl text-slate-600 dark:text-slate-400 mb-2">عذراً، لا تمتلك الصلاحية للوصول إلى هذه الصفحة</p>
        <p class="text-slate-500 dark:text-slate-500 mb-8">يرجى التواصل مع مدير النظام إذا كنت تعتقد أن هذا خطأ</p>
        
        <div class="flex items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                العودة للرئيسية
            </a>
            <button onclick="history.back()" class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                العودة للخلف
            </button>
        </div>
    </div>
</div>
@endsection
