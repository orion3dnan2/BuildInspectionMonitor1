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
                <label class="block text-sm text-slate-600 mb-3 text-right">الصلاحية <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="admin" {{ old('role', $user->role) == 'admin' ? 'checked' : '' }} class="peer sr-only" required>
                        <div class="p-4 border-2 border-slate-200 rounded-lg peer-checked:border-sky-500 peer-checked:bg-sky-50 transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-sky-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-700">مدير</span>
                            </div>
                            <ul class="text-xs text-slate-500 space-y-1 mr-2">
                                <li>جميع الصلاحيات</li>
                                <li>إدارة المستخدمين</li>
                                <li>إدارة الإعدادات</li>
                                <li>إدخال وتعديل وحذف السجلات</li>
                            </ul>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="supervisor" {{ old('role', $user->role) == 'supervisor' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-4 border-2 border-slate-200 rounded-lg peer-checked:border-amber-500 peer-checked:bg-amber-50 transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-700">مشرف</span>
                            </div>
                            <ul class="text-xs text-slate-500 space-y-1 mr-2">
                                <li>إدارة الإعدادات</li>
                                <li>إدخال وتعديل السجلات</li>
                                <li>استيراد البيانات</li>
                                <li>بدون إدارة المستخدمين</li>
                            </ul>
                        </div>
                    </label>

                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="user" {{ old('role', $user->role) == 'user' ? 'checked' : '' }} class="peer sr-only">
                        <div class="p-4 border-2 border-slate-200 rounded-lg peer-checked:border-slate-500 peer-checked:bg-slate-50 transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-700">مستخدم</span>
                            </div>
                            <ul class="text-xs text-slate-500 space-y-1 mr-2">
                                <li>عرض السجلات</li>
                                <li>البحث والاستعلام</li>
                                <li>عرض التقارير</li>
                                <li>بدون تعديل أو حذف</li>
                            </ul>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
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
@endsection
