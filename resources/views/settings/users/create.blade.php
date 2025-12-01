@extends('layouts.app')

@section('title', 'إضافة مستخدم - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-4xl mx-auto">
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
        <h2 class="text-lg font-bold text-sky-700 text-center mb-6">بيانات المستخدم</h2>
        
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
                    <label for="rank" class="block text-sm text-slate-600 mb-2 text-right">الرتبة</label>
                    <input type="text" name="rank" id="rank" value="{{ old('rank') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                </div>

                <div>
                    <label for="office" class="block text-sm text-slate-600 mb-2 text-right">المكتب/الجهة</label>
                    <input type="text" name="office" id="office" value="{{ old('office') }}"
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
                <label for="role" class="block text-sm text-slate-600 mb-2 text-right">الدور <span class="text-red-500">*</span></label>
                <select name="role" id="role" required
                    class="w-full px-3 py-2 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm @error('role') border-red-400 @enderror">
                    <option value="">-- اختر الدور --</option>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-2">
                    <strong>المدير:</strong> يمكنه الوصول لجميع الصفحات والإعدادات |
                    <strong>المفتش:</strong> يمكنه الوصول للصفحات المسموحة فقط
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white px-5 py-2 rounded font-medium transition text-sm">
                    حفظ المستخدم
                </button>
                <a href="{{ route('settings.users.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-600 px-5 py-2 rounded font-medium transition text-sm">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
