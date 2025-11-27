@extends('layouts.app')

@section('title', 'تفاصيل الموظف')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للموظفين
    </a>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">{{ $employee->full_name }}</h1>
        <a href="{{ route('admin.employees.edit', $employee) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="text-center mb-6">
                <div class="w-24 h-24 bg-sky-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl font-bold text-sky-600">{{ mb_substr($employee->first_name, 0, 1) }}</span>
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ $employee->full_name }}</h2>
                <p class="text-slate-500">{{ $employee->job_title ?? 'غير محدد' }}</p>
                @switch($employee->status)
                    @case('active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800 mt-2">نشط</span>
                        @break
                    @case('inactive')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800 mt-2">غير نشط</span>
                        @break
                    @case('on_leave')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 mt-2">في إجازة</span>
                        @break
                    @case('terminated')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mt-2">منتهي الخدمة</span>
                        @break
                @endswitch
            </div>
            
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">الرقم الوظيفي</dt>
                    <dd class="text-sm font-medium text-slate-800">{{ $employee->employee_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">الرقم المدني</dt>
                    <dd class="text-sm font-medium text-slate-800">{{ $employee->civil_id }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">القسم</dt>
                    <dd class="text-sm font-medium text-slate-800">{{ $employee->department?->name ?? 'غير محدد' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">الرتبة</dt>
                    <dd class="text-sm font-medium text-slate-800">{{ $employee->rank ?? 'غير محدد' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-slate-500">تاريخ التعيين</dt>
                    <dd class="text-sm font-medium text-slate-800">{{ $employee->hire_date?->format('Y/m/d') ?? 'غير محدد' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">أرصدة الإجازات</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-slate-500">الإجازات السنوية</span>
                        <span class="text-sm font-medium text-slate-800">{{ $employee->annual_leave_balance }} يوم</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-sky-500 h-2 rounded-full" style="width: {{ min(100, ($employee->annual_leave_balance / 30) * 100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-slate-500">الإجازات المرضية</span>
                        <span class="text-sm font-medium text-slate-800">{{ $employee->sick_leave_balance }} يوم</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, ($employee->sick_leave_balance / 15) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">سجل الحضور (آخر 30 يوم)</h2>
            </div>
            
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">التاريخ</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحضور</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الانصراف</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employee->attendances as $attendance)
                    <tr class="hover:bg-slate-50">
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            لا يوجد سجل حضور
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">طلبات الإجازات الأخيرة</h2>
            </div>
            
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">النوع</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">من</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">إلى</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الأيام</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($employee->leaveRequests as $leave)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $leave->leave_type_label }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $leave->start_date->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $leave->end_date->format('Y/m/d') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $leave->days_count }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @switch($leave->status)
                                    @case('pending') bg-amber-100 text-amber-800 @break
                                    @case('approved') bg-emerald-100 text-emerald-800 @break
                                    @case('rejected') bg-red-100 text-red-800 @break
                                @endswitch
                            ">{{ $leave->status_label }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            لا توجد طلبات إجازات
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
