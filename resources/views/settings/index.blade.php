@extends('layouts.app')

@section('title', 'الإعدادات - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة الرئيسية
        </a>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-700">الإعدادات</h1>
                <p class="text-slate-400 text-sm">إدارة إعدادات النظام والبيانات الأساسية</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 mb-6">
        <div class="flex border-b border-slate-200">
            <a href="{{ route('settings.ports.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition border-b-2 border-transparent text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                المنافذ
            </a>
            <a href="{{ route('settings.stations.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition border-b-2 border-transparent text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                المخافر
            </a>
            <a href="{{ route('settings.users.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition border-b-2 border-transparent text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                المستخدمون
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <p class="text-center text-slate-400 py-8 text-sm">اختر قسماً من الأعلى لعرض الإعدادات</p>
    </div>
</div>
@endsection
