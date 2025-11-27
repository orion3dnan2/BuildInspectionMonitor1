@extends('layouts.app')

@section('title', 'صلاحيات المستخدم: ' . $user->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">صلاحيات المستخدم: {{ $user->name }}</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">تعديل أدوار وصلاحيات المستخدم</p>
        </div>
        <a href="{{ route('settings.users.show', $user) }}" class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            رجوع
        </a>
    </div>

    <form action="{{ route('settings.users.update-permissions', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">معلومات المستخدم</h2>
            
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-sky-100 dark:bg-sky-900 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-sky-600 dark:text-sky-400">{{ mb_substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-slate-500 dark:text-slate-400">{{ $user->username }} - {{ $user->role_name }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">الأدوار</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">اختر الأدوار التي تريد تعيينها للمستخدم. الصلاحيات المرتبطة بالأدوار ستُطبق تلقائياً.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($roles as $role)
                <label class="flex items-start gap-3 p-4 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition {{ in_array($role->id, $userRoleIds) ? 'bg-sky-50 dark:bg-sky-900/20 border-sky-300 dark:border-sky-700' : '' }}">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoleIds)) ? 'checked' : '' }} class="mt-1 w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-sky-500 focus:ring-sky-500">
                    <div>
                        <span class="font-medium text-slate-800 dark:text-white block">{{ $role->name }}</span>
                        <span class="text-sm text-slate-500 dark:text-slate-400">{{ $role->description }}</span>
                        <span class="text-xs text-sky-500 block mt-1">{{ $role->permissions->count() }} صلاحية</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white">صلاحيات إضافية / استثناءات</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">يمكنك منح أو رفض صلاحيات محددة للمستخدم بشكل مباشر</p>
                </div>
            </div>
            
            <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                <p class="text-sm text-amber-700 dark:text-amber-400">
                    <svg class="w-5 h-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <strong>موروثة:</strong> الصلاحية موجودة في أحد الأدوار المعينة |
                    <strong>ممنوحة:</strong> صلاحية إضافية للمستخدم |
                    <strong>مرفوضة:</strong> صلاحية محظورة على المستخدم حتى لو كانت في الدور
                </p>
            </div>
            
            <div class="space-y-6">
                @foreach($groupedPermissions as $module => $modulePermissions)
                <div class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                    <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3">
                        <span class="font-medium text-slate-700 dark:text-slate-200">{{ $modules[$module] ?? $module }}</span>
                    </div>
                    <div class="p-4">
                        <table class="w-full">
                            <thead>
                                <tr class="text-xs text-slate-500 dark:text-slate-400">
                                    <th class="text-right pb-2">الصلاحية</th>
                                    <th class="text-center pb-2 w-24">موروثة</th>
                                    <th class="text-center pb-2 w-24">ممنوحة</th>
                                    <th class="text-center pb-2 w-24">مرفوضة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @foreach($modulePermissions as $permission)
                                @php
                                    $currentStatus = isset($userPermissionData[$permission->id]) 
                                        ? ($userPermissionData[$permission->id] ? 'granted' : 'denied') 
                                        : 'inherit';
                                @endphp
                                <tr>
                                    <td class="py-2 text-sm text-slate-600 dark:text-slate-300">{{ explode(' ', $permission->name)[0] }}</td>
                                    <td class="py-2 text-center">
                                        <input type="radio" name="permissions[{{ $permission->id }}]" value="inherit" {{ $currentStatus === 'inherit' ? 'checked' : '' }} class="w-4 h-4 text-slate-500 border-slate-300 dark:border-slate-600 focus:ring-slate-500">
                                    </td>
                                    <td class="py-2 text-center">
                                        <input type="radio" name="permissions[{{ $permission->id }}]" value="granted" {{ $currentStatus === 'granted' ? 'checked' : '' }} class="w-4 h-4 text-emerald-500 border-slate-300 dark:border-slate-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="py-2 text-center">
                                        <input type="radio" name="permissions[{{ $permission->id }}]" value="denied" {{ $currentStatus === 'denied' ? 'checked' : '' }} class="w-4 h-4 text-red-500 border-slate-300 dark:border-slate-600 focus:ring-red-500">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('settings.users.show', $user) }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">إلغاء</a>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">حفظ الصلاحيات</button>
        </div>
    </form>
</div>
@endsection
