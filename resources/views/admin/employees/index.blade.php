@extends('layouts.app')

@section('title', 'إدارة الموظفين')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">إدارة الموظفين</h1>
            <p class="text-slate-500 mt-1">إدارة بيانات الموظفين والموارد البشرية</p>
        </div>
        <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            إضافة موظف جديد
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-100">
        <form action="{{ route('admin.employees.index') }}" method="GET" class="flex flex-wrap gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم أو الرقم الوظيفي أو المدني..." class="flex-1 min-w-[200px] px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            <select name="department_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الأقسام</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الحالات</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>منتهي الخدمة</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                بحث
            </button>
        </form>
    </div>

    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الرقم الوظيفي</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الاسم</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">القسم</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الوظيفة</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">الإجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($employees as $employee)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 text-slate-600">{{ $employee->employee_number }}</td>
                <td class="px-6 py-4">
                    <span class="font-medium text-slate-800">{{ $employee->full_name }}</span>
                </td>
                <td class="px-6 py-4 text-slate-600">{{ $employee->department?->name ?? '-' }}</td>
                <td class="px-6 py-4 text-slate-600">{{ $employee->job_title ?? '-' }}</td>
                <td class="px-6 py-4">
                    @switch($employee->status)
                        @case('active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">نشط</span>
                            @break
                        @case('inactive')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">غير نشط</span>
                            @break
                        @case('on_leave')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">في إجازة</span>
                            @break
                        @case('terminated')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">منتهي الخدمة</span>
                            @break
                    @endswitch
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.employees.show', $employee) }}" class="p-2 text-slate-400 hover:text-sky-600 transition" title="عرض">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('admin.employees.edit', $employee) }}" class="p-2 text-slate-400 hover:text-amber-600 transition" title="تعديل">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
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
                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                    لا يوجد موظفين مسجلين
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($employees->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $employees->links() }}
    </div>
    @endif
</div>
@endsection
