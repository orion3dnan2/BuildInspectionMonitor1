@extends('layouts.app')

@section('title', 'تفاصيل القسم')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.departments.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للأقسام
    </a>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">{{ $department->name }}</h1>
        <a href="{{ route('admin.departments.edit', $department) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">معلومات القسم</h2>
            
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm text-slate-500">رمز القسم</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $department->code }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">المدير</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $department->manager?->name ?? 'غير محدد' }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">الحالة</dt>
                    <dd>
                        @if($department->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">نشط</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">غير نشط</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">عدد الموظفين</dt>
                    <dd class="text-base font-medium text-slate-800">{{ $department->employees->count() }}</dd>
                </div>
                @if($department->description)
                <div>
                    <dt class="text-sm text-slate-500">الوصف</dt>
                    <dd class="text-base text-slate-800">{{ $department->description }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-lg font-bold text-slate-800">موظفو القسم</h2>
            </div>
            
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الاسم</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الوظيفة</th>
                        <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($department->employees as $employee)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.employees.show', $employee) }}" class="font-medium text-sky-600 hover:text-sky-700">{{ $employee->full_name }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $employee->job_title ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($employee->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">نشط</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">{{ $employee->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                            لا يوجد موظفين في هذا القسم
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
