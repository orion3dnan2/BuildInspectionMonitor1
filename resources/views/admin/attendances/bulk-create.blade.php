@extends('layouts.app')

@section('title', 'تسجيل حضور جماعي')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة لسجل الحضور
    </a>
    <h1 class="text-2xl font-bold text-slate-800">تسجيل حضور جماعي</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.attendances.bulk-store') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">التاريخ <span class="text-red-500">*</span></label>
            <input type="date" name="date" value="{{ old('date', today()->format('Y-m-d')) }}" required class="w-64 px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
        </div>

        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-500">الموظف</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-500">القسم</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-500">الحالة</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-500">الحضور</th>
                    <th class="text-right px-4 py-3 text-sm font-medium text-slate-500">الانصراف</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($employees as $index => $employee)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <input type="hidden" name="attendances[{{ $index }}][employee_id]" value="{{ $employee->id }}">
                        <span class="font-medium text-slate-800">{{ $employee->full_name }}</span>
                    </td>
                    <td class="px-4 py-3 text-slate-600">{{ $employee->department?->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <select name="attendances[{{ $index }}][status]" class="px-3 py-1.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                            <option value="present">حاضر</option>
                            <option value="absent">غائب</option>
                            <option value="late">متأخر</option>
                            <option value="leave">إجازة</option>
                            <option value="holiday">عطلة</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <input type="time" name="attendances[{{ $index }}][check_in]" value="08:00" class="px-3 py-1.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                    </td>
                    <td class="px-4 py-3">
                        <input type="time" name="attendances[{{ $index }}][check_out]" class="px-3 py-1.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center gap-4 mt-6 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                حفظ الحضور
            </button>
            <a href="{{ route('admin.attendances.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
