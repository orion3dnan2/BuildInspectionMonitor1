@extends('layouts.app')

@section('title', 'الإعدادات - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">الإعدادات</h1>
    <p class="text-gray-600">إدارة إعدادات النظام</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @if(auth()->user()->isAdmin())
    <a href="{{ route('settings.users.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border-r-4 border-blue-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">إدارة المستخدمين</h3>
                <p class="text-gray-500">إضافة وتعديل المستخدمين</p>
            </div>
        </div>
    </a>
    @endif

    <a href="{{ route('settings.stations.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border-r-4 border-green-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">إدارة المخافر</h3>
                <p class="text-gray-500">إضافة وتعديل المخافر</p>
            </div>
        </div>
    </a>

    <a href="{{ route('settings.ports.index') }}" class="block bg-white rounded-xl shadow-sm hover:shadow-md transition p-6 border-r-4 border-yellow-500">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">إدارة المنافذ</h3>
                <p class="text-gray-500">إضافة وتعديل المنافذ</p>
            </div>
        </div>
    </a>
</div>
@endsection
