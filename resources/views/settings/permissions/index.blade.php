@extends('layouts.app')

@section('title', 'مركز الصلاحيات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">مركز الصلاحيات</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">إدارة الأدوار والصلاحيات في النظام</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('settings.permissions.roles') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                إدارة الأدوار
            </a>
            <form action="{{ route('settings.permissions.sync') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    مزامنة الصلاحيات
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($roles as $role)
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-sky-100 dark:bg-sky-900/50 flex items-center justify-center">
                    <svg class="w-6 h-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $role->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $role->description }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                <span>{{ $role->permissions->count() }} صلاحية</span>
                @if(!$role->is_system)
                    <a href="{{ route('settings.permissions.edit-role', $role) }}" class="text-sky-500 hover:text-sky-600">تعديل</a>
                @else
                    <span class="text-slate-400">دور أساسي</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">جدول الصلاحيات الكامل</h2>
            <p class="text-slate-500 dark:text-slate-400 mt-1">عرض جميع صلاحيات النظام مصنفة حسب الوحدات</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">الوحدة / الصلاحية</th>
                        @foreach($roles as $role)
                        <th class="px-4 py-4 text-center text-sm font-medium text-slate-600 dark:text-slate-300">{{ $role->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($groupedPermissions as $module => $modulePermissions)
                    <tr class="bg-slate-100 dark:bg-slate-700/30">
                        <td colspan="{{ $roles->count() + 1 }}" class="px-6 py-3">
                            <span class="font-bold text-slate-700 dark:text-slate-200">{{ $modules[$module] ?? $module }}</span>
                        </td>
                    </tr>
                    @foreach($modulePermissions as $permission)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20">
                        <td class="px-6 py-3 text-sm text-slate-600 dark:text-slate-300 pr-10">
                            {{ $permission->name }}
                            <span class="text-xs text-slate-400 dark:text-slate-500 block">{{ $permission->key }}</span>
                        </td>
                        @foreach($roles as $role)
                        <td class="px-4 py-3 text-center">
                            @if($role->permissions->contains('id', $permission->id))
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-slate-100 dark:bg-slate-700 text-slate-400 rounded-full">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
