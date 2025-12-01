@extends('layouts.app')

@section('title', 'إدارة الأدوار')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">إدارة الأدوار</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">إدارة أدوار المستخدمين وصلاحياتهم</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('settings.permissions.index') }}" class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                رجوع
            </a>
            <a href="{{ route('settings.permissions.create-role') }}" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة دور جديد
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">#</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">اسم الدور</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">المعرف</th>
                        <th class="px-6 py-4 text-right text-sm font-medium text-slate-600 dark:text-slate-300">الوصف</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-slate-600 dark:text-slate-300">الصلاحيات</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-slate-600 dark:text-slate-300">المستخدمين</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-slate-600 dark:text-slate-300">النوع</th>
                        <th class="px-6 py-4 text-center text-sm font-medium text-slate-600 dark:text-slate-300">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach($roles as $role)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/20">
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-800 dark:text-white">{{ $role->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-sm bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-slate-600 dark:text-slate-300">{{ $role->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $role->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 dark:bg-sky-900/50 text-sky-800 dark:text-sky-300">
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300">
                                {{ $role->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($role->is_system)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300">
                                    أساسي
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                    مخصص
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('settings.permissions.edit-role', $role) }}" class="p-2 text-sky-500 hover:bg-sky-50 dark:hover:bg-sky-900/20 rounded-lg transition" title="تعديل">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if(!$role->is_system)
                                <form action="{{ route('settings.permissions.destroy-role', $role) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="حذف">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
