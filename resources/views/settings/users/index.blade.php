@extends('layouts.app')

@section('title', 'إدارة المستخدمين - نظام الرقابة والتفتيش')

@section('content')
<div class="w-full">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 text-sm mb-4 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة الرئيسية
        </a>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-700">الإعدادات</h1>
                <p class="text-slate-400 text-sm">إدارة إعدادات النظام والبيانات الأساسية</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 mb-6">
        <div class="flex border-b border-slate-200">
            <a href="{{ route('settings.ports.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition border-b-2 border-transparent text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                المنافذ
            </a>
            <a href="{{ route('settings.stations.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition border-b-2 border-transparent text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                المخافر
            </a>
            <a href="{{ route('settings.users.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-slate-700 bg-white border-b-2 border-sky-500 font-medium text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                المستخدمون
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
        <div class="flex gap-4">
            <div class="flex-1 relative">
                <input type="text" id="searchUsers" placeholder="ابحث عن مستخدم..."
                    class="w-full px-3 py-2 pr-10 border border-slate-300 rounded focus:ring-1 focus:ring-sky-400 focus:border-sky-400 text-sm">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('settings.users.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-slate-800 px-4 py-2 rounded flex items-center gap-2 transition text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة مستخدم
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-bold text-slate-700">المستخدمون</h2>
            <span class="text-sm text-slate-400">إجمالي المستخدمين: {{ $users->total() }}</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">اسم المستخدم</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الاسم الكامل</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الصلاحية</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الأنظمة المتاحة</th>
                        <th class="px-4 py-3 text-right font-medium text-slate-500">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100" id="usersTable">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 user-row" data-name="{{ strtolower($user->name) }}" data-username="{{ strtolower($user->username) }}">
                        <td class="px-4 py-3 text-slate-600">{{ $user->username }}</td>
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $user->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-xs rounded-full inline-flex items-center gap-1
                                @if($user->role == 'admin') bg-sky-100 text-sky-700
                                @elseif($user->role == 'supervisor') bg-amber-100 text-amber-700
                                @else bg-slate-100 text-slate-600 @endif">
                                {{ $user->role_name }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->role == 'admin')
                                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">جميع الأنظمة</span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->system_access_labels as $label)
                                        <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700">{{ $label }}</span>
                                    @empty
                                        <span class="text-xs text-slate-400">لا يوجد</span>
                                    @endforelse
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('settings.users.edit', $user) }}" class="bg-slate-100 text-slate-600 px-3 py-1.5 rounded text-xs flex items-center gap-1 hover:bg-slate-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    تعديل
                                </a>
                                @if($user->id != auth()->id())
                                <form action="{{ route('settings.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-50 text-red-600 px-3 py-1.5 rounded text-xs flex items-center gap-1 hover:bg-red-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-slate-400">لا يوجد مستخدمين</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="mt-4 pt-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
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
