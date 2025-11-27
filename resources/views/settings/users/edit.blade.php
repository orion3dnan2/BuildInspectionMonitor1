@extends('layouts.app')

@section('title', 'تعديل مستخدم - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('settings.users.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة لقائمة المستخدمين
        </a>
        <h1 class="text-xl font-bold text-slate-700">تعديل مستخدم</h1>
        <p class="text-slate-400 text-sm">تعديل بيانات المستخدم: {{ $user->name }}</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-red-700 text-center mb-6">بيانات المستخدم</h2>
        
        <form action="{{ route('settings.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 mb-6">
                <div>
                    <label for="name" class="block text-sm text-slate-600 mb-2 text-right">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm text-slate-600 mb-2 text-right">اسم المستخدم <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('username') border-red-400 @enderror">
                    @error('username')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm text-slate-600 mb-2 text-right">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rank" class="block text-sm text-slate-600 mb-2 text-right">الرتبة</label>
                    <input type="text" name="rank" id="rank" value="{{ old('rank', $user->rank) }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm text-slate-600 mb-2 text-right">كلمة المرور الجديدة</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('password') border-red-400 @enderror"
                        placeholder="اتركه فارغاً للإبقاء على كلمة المرور الحالية">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm text-slate-600 mb-2 text-right">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm text-slate-600 mb-3 text-right">نوع الحساب <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="role" value="admin" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }} 
                            class="w-4 h-4 text-sky-500 border-slate-300 focus:ring-sky-400" id="role_admin">
                        <span class="text-sm text-slate-700">مدير (جميع الصلاحيات)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="role" value="user" {{ old('role', $user->role) == 'user' || old('role', $user->role) == 'supervisor' ? 'checked' : '' }} 
                            class="w-4 h-4 text-sky-500 border-slate-300 focus:ring-sky-400" id="role_user">
                        <span class="text-sm text-slate-700">مستخدم (صلاحيات مخصصة)</span>
                    </label>
                </div>
                @error('role')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            @php
                $userPermissions = old('permissions', $user->permissions ?? []);
            @endphp

            <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200" id="permissions_section">
                <label class="block text-sm font-medium text-slate-700 mb-4">الصلاحيات المتاحة</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(\App\Models\User::availablePermissions() as $key => $label)
                    <label class="flex items-center gap-3 p-3 bg-white rounded border border-slate-200 cursor-pointer hover:border-sky-300 transition">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                            {{ in_array($key, $userPermissions) ? 'checked' : '' }}
                            class="w-4 h-4 text-sky-500 border-slate-300 rounded focus:ring-sky-400 permission-checkbox">
                        <span class="text-sm text-slate-600">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-slate-400 mt-3">اختر الصلاحيات التي تريد منحها لهذا المستخدم</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-5 py-2 rounded font-medium transition text-sm">
                    حفظ التغييرات
                </button>
                <a href="{{ route('settings.users.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-600 px-5 py-2 rounded font-medium transition text-sm">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const adminRadio = document.getElementById('role_admin');
    const userRadio = document.getElementById('role_user');
    const permissionsSection = document.getElementById('permissions_section');
    const checkboxes = document.querySelectorAll('.permission-checkbox');

    function togglePermissions() {
        if (adminRadio.checked) {
            permissionsSection.style.opacity = '0.5';
            permissionsSection.style.pointerEvents = 'none';
            checkboxes.forEach(cb => cb.checked = true);
        } else {
            permissionsSection.style.opacity = '1';
            permissionsSection.style.pointerEvents = 'auto';
        }
    }

    adminRadio.addEventListener('change', togglePermissions);
    userRadio.addEventListener('change', togglePermissions);
    togglePermissions();
});
</script>
@endpush
@endsection
