@extends('layouts.app')

@section('title', 'إضافة مستخدم - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('settings.users.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة لقائمة المستخدمين
        </a>
        <h1 class="text-xl font-bold text-slate-700">إضافة مستخدم جديد</h1>
        <p class="text-slate-400 text-sm">أدخل بيانات المستخدم واختر الدور المناسب</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-red-700 text-center mb-6">بيانات المستخدم</h2>
        
        <form action="{{ route('settings.users.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5 mb-6">
                <div>
                    <label for="name" class="block text-sm text-slate-600 mb-2 text-right">الاسم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-sm text-slate-600 mb-2 text-right">اسم المستخدم <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('username') border-red-400 @enderror">
                    @error('username')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm text-slate-600 mb-2 text-right">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rank" class="block text-sm text-slate-600 mb-2 text-right">الرتبة</label>
                    <input type="text" name="rank" id="rank" value="{{ old('rank') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm text-slate-600 mb-2 text-right">كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm text-slate-600 mb-2 text-right">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                </div>
            </div>

            <div class="mb-6">
                <label for="role_id" class="block text-sm text-slate-600 mb-2 text-right">الدور <span class="text-red-500">*</span></label>
                <select name="role_id" id="role_id" required
                    class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('role_id') border-red-400 @enderror">
                    <option value="">-- اختر الدور --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}
                            data-slug="{{ $role->slug }}">
                            {{ $role->name }}
                            @if($role->description)
                                - {{ $role->description }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-2">
                    <a href="{{ route('settings.permissions.roles') }}" class="text-sky-500 hover:text-sky-600">
                        إدارة الأدوار والصلاحيات
                    </a>
                </p>
            </div>

            <div class="mb-6 p-4 bg-sky-50 rounded-lg border border-sky-200" id="system_access_section">
                <label class="block text-sm font-medium text-slate-700 mb-4">الوصول للأنظمة</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach(\App\Models\User::availableSystems() as $key => $label)
                    <label class="flex items-center gap-3 p-3 bg-white rounded border border-slate-200 cursor-pointer hover:border-sky-300 transition">
                        <input type="checkbox" name="system_access[]" value="{{ $key }}" 
                            {{ in_array($key, old('system_access', [])) ? 'checked' : '' }}
                            class="w-4 h-4 text-sky-500 border-slate-300 rounded focus:ring-sky-400 system-access-checkbox">
                        <span class="text-sm text-slate-600">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-slate-400 mt-3">حدد الأنظمة التي يمكن للمستخدم الوصول إليها</p>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-5 py-2 rounded font-medium transition text-sm">
                    حفظ المستخدم
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
    const roleSelect = document.getElementById('role_id');
    const systemAccessSection = document.getElementById('system_access_section');
    const systemAccessCheckboxes = document.querySelectorAll('.system-access-checkbox');

    function toggleSections() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleSlug = selectedOption ? selectedOption.getAttribute('data-slug') : '';
        
        if (roleSlug === 'admin') {
            systemAccessSection.style.opacity = '0.5';
            systemAccessSection.style.pointerEvents = 'none';
            systemAccessCheckboxes.forEach(cb => cb.checked = true);
        } else {
            systemAccessSection.style.opacity = '1';
            systemAccessSection.style.pointerEvents = 'auto';
        }
    }

    roleSelect.addEventListener('change', toggleSections);
    toggleSections();
});
</script>
@endpush
@endsection
