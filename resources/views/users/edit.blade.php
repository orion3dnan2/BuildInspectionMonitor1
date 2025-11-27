@extends('layouts.app')

@section('title', 'تعديل مستخدم - نظام التفتيش والمراقبة')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">تعديل المستخدم</h1>
    <p class="text-gray-600 mt-1">تعديل بيانات المستخدم</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">اسم المستخدم <span class="text-red-500">*</span></label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror">
                @error('username')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الدور <span class="text-red-500">*</span></label>
                <select name="role" id="role" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                    <option value="inspector" {{ old('role', $user->role) === 'inspector' ? 'selected' : '' }}>مفتش</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>مدير</option>
                </select>
                @error('role')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة <span class="text-gray-400 text-xs">(اتركها فارغة للإبقاء على القديمة)</span></label>
                <input type="password" name="password" id="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="rank" class="block text-sm font-medium text-gray-700 mb-2">الرتبة</label>
                <input type="text" name="rank" id="rank" value="{{ old('rank', $user->rank) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('rank') border-red-500 @enderror">
                @error('rank')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="office" class="block text-sm font-medium text-gray-700 mb-2">المكتب</label>
                <input type="text" name="office" id="office" value="{{ old('office', $user->office) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('office') border-red-500 @enderror">
                @error('office')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                تحديث المستخدم
            </button>
            <a href="{{ route('users.index') }}" class="px-8 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
