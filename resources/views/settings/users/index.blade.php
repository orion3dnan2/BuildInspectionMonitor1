@extends('layouts.app')

@section('title', 'إدارة المستخدمين - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600">الإعدادات</span>
            <span class="text-gray-400">/</span>
            <span class="text-gray-600">المستخدمين</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">الإعدادات</h1>
        <p class="text-gray-500">إدارة إعدادات النظام والبيانات الأساسية</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="flex border-b border-gray-200">
            <a href="{{ route('settings.users.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-gray-700 bg-white border-b-2 border-blue-600 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                المستخدمين
            </a>
            <a href="{{ route('settings.stations.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition border-b-2 border-transparent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                المخافر
            </a>
            <a href="{{ route('settings.ports.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition border-b-2 border-transparent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                المنافذ
            </a>
        </div>

        <div class="p-8">
            <div class="flex gap-4 mb-8">
                <div class="flex-1 relative">
                    <input type="text" id="searchUsers" placeholder="ابحث عن مستخدم..."
                        class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('settings.users.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة مستخدم
                </a>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">المستخدمون</h2>
                <span class="text-sm text-gray-500">إجمالي المستخدمين: {{ $users->total() }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">اسم المستخدم</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الاسم الكامل</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الصلاحية</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="usersTable">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 user-row" data-name="{{ strtolower($user->name) }}" data-username="{{ strtolower($user->username) }}">
                            <td class="py-4 px-4 text-sm text-gray-600">{{ $user->username }}</td>
                            <td class="py-4 px-4 text-sm font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 text-xs rounded-full inline-flex items-center gap-1
                                    @if($user->role == 'admin') bg-green-100 text-green-700
                                    @elseif($user->role == 'supervisor') bg-yellow-100 text-yellow-700
                                    @else bg-blue-100 text-blue-700 @endif">
                                    {{ $user->role_name }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('settings.users.edit', $user) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    @if($user->id != auth()->id())
                                    <form action="{{ route('settings.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-12 text-center text-gray-500">لا يوجد مستخدمين</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="mt-6 pt-6 border-t border-gray-100">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('searchUsers').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.user-row').forEach(function(row) {
        const name = row.dataset.name;
        const username = row.dataset.username;
        if (name.includes(search) || username.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
