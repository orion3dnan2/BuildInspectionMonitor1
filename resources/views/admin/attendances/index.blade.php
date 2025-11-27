@extends('layouts.app')

@section('title', 'سجل الحضور')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">سجل الحضور</h1>
            <p class="text-slate-500 mt-1">تسجيل ومتابعة حضور وانصراف الموظفين</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.attendances.report') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تقرير الحضور
            </a>
            <a href="{{ route('admin.attendances.bulk-create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                تسجيل جماعي
            </a>
            <a href="{{ route('admin.attendances.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                تسجيل حضور
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-100">
        <form action="{{ route('admin.attendances.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="date" name="date" value="{{ request('date', today()->format('Y-m-d')) }}" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <select name="department_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الأقسام</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الحالات</option>
                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>إجازة</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                بحث
            </button>
        </form>
    </div>

    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الموظف</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">القسم</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">التاريخ</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحضور</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الانصراف</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">الإجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($attendances as $attendance)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 font-medium text-slate-800">{{ $attendance->employee->full_name }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $attendance->employee->department?->name ?? '-' }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $attendance->date->format('Y/m/d') }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $attendance->check_in ?? '-' }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $attendance->check_out ?? '-' }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($attendance->status)
                            @case('present') bg-emerald-100 text-emerald-800 @break
                            @case('absent') bg-red-100 text-red-800 @break
                            @case('late') bg-amber-100 text-amber-800 @break
                            @case('leave') bg-sky-100 text-sky-800 @break
                            @case('holiday') bg-purple-100 text-purple-800 @break
                        @endswitch
                    ">{{ $attendance->status_label }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.attendances.edit', $attendance) }}" class="p-2 text-slate-400 hover:text-amber-600 transition" title="تعديل">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.attendances.destroy', $attendance) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition" title="حذف">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                    لا توجد سجلات حضور لهذا التاريخ
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($attendances->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $attendances->links() }}
    </div>
    @endif
</div>
@endsection
