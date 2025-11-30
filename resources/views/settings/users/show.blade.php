@extends('layouts.app')

@section('title', 'عرض مستخدم - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('settings.users.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة لقائمة المستخدمين
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-700">عرض مستخدم</h1>
                <p class="text-slate-400 text-sm">بيانات المستخدم: {{ $user->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('settings.users.permissions', $user) }}" 
                   class="inline-flex items-center gap-2 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    إدارة الصلاحيات
                </a>
                <a href="{{ route('settings.users.edit', $user) }}" 
                   class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-4 py-2 rounded-lg text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-red-700 text-center mb-6">بيانات المستخدم</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 mb-6">
            <div>
                <label class="block text-sm text-slate-500 mb-1 text-right">الاسم الكامل</label>
                <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
                    {{ $user->name }}
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-1 text-right">اسم المستخدم</label>
                <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
                    {{ $user->username }}
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-1 text-right">البريد الإلكتروني</label>
                <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
                    {{ $user->email ?? 'غير محدد' }}
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-1 text-right">الرتبة</label>
                <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
                    {{ $user->rank ?? 'غير محدد' }}
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-1 text-right">المكتب</label>
                <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
                    {{ $user->office ?? 'غير محدد' }}
                </div>
            </div>

            <div>
                <label class="block text-sm text-slate-500 mb-1 text-right">الدور</label>
                <div class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded text-sm">
                    @php
                        $roleColors = [
                            'admin' => 'bg-red-100 text-red-700',
                            'supervisor' => 'bg-amber-100 text-amber-700',
                            'user' => 'bg-sky-100 text-sky-700',
                        ];
                        $roleNames = [
                            'admin' => 'مدير',
                            'supervisor' => 'مشرف',
                            'user' => 'مستخدم',
                        ];
                        $roleClass = $roleColors[$user->role] ?? 'bg-slate-100 text-slate-700';
                        $userRoles = $user->roles;
                    @endphp
                    @if($userRoles->count() > 0)
                        @foreach($userRoles as $role)
                            <span class="inline-flex px-2 py-1 rounded text-xs {{ $roleColors[$role->slug] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    @else
                        <span class="inline-flex px-2 py-1 rounded text-xs {{ $roleClass }}">
                            {{ $roleNames[$user->role] ?? $user->role }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-6 p-4 bg-sky-50 rounded-lg border border-sky-200">
            <label class="block text-sm font-medium text-slate-700 mb-4">الوصول للأنظمة</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @php
                    $userSystemAccess = $user->system_access ?? [];
                    $availableSystems = \App\Models\User::availableSystems();
                @endphp
                @foreach($availableSystems as $key => $label)
                <div class="flex items-center gap-3 p-3 bg-white rounded border border-slate-200">
                    @if(in_array($key, $userSystemAccess))
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm text-slate-700">{{ $label }}</span>
                    @else
                        <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="text-sm text-slate-400">{{ $label }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
            <label class="block text-sm font-medium text-slate-700 mb-4">معلومات إضافية</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">تاريخ الإنشاء:</span>
                    <span class="text-slate-700">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">آخر تحديث:</span>
                    <span class="text-slate-700">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('settings.users.edit', $user) }}" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-5 py-2 rounded font-medium transition text-sm">
                تعديل المستخدم
            </a>
            <a href="{{ route('settings.users.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-600 px-5 py-2 rounded font-medium transition text-sm">
                العودة للقائمة
            </a>
        </div>
    </div>
</div>
@endsection
