@extends('layouts.app')

@section('title', 'تقرير الحضور')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة لسجل الحضور
    </a>
    <h1 class="text-2xl font-bold text-slate-800">تقرير الحضور</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-6">
    <form action="{{ route('admin.attendances.report') }}" method="GET" class="flex flex-wrap gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">من تاريخ</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">إلى تاريخ</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">القسم</label>
            <select name="department_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الأقسام</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                عرض التقرير
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الموظف</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">القسم</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">حاضر</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">غائب</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">متأخر</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">إجازة</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">المجموع</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($employees as $employee)
            @php
                $present = $employee->attendances->where('status', 'present')->count();
                $absent = $employee->attendances->where('status', 'absent')->count();
                $late = $employee->attendances->where('status', 'late')->count();
                $leave = $employee->attendances->where('status', 'leave')->count();
                $total = $employee->attendances->count();
            @endphp
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 font-medium text-slate-800">{{ $employee->full_name }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $employee->department?->name ?? '-' }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 text-sm font-medium">{{ $present }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-800 text-sm font-medium">{{ $absent }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-800 text-sm font-medium">{{ $late }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-sky-100 text-sky-800 text-sm font-medium">{{ $leave }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="font-medium text-slate-800">{{ $total }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                    لا توجد بيانات للفترة المحددة
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
