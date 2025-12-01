@extends('layouts.app')

@section('title', 'تفاصيل طلب الإجازة')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.leave-requests.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة لطلبات الإجازات
    </a>
    <h1 class="text-2xl font-bold text-slate-800">تفاصيل طلب الإجازة</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-slate-800">معلومات الطلب</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @switch($leaveRequest->status)
                        @case('pending') bg-amber-100 text-amber-800 @break
                        @case('approved') bg-emerald-100 text-emerald-800 @break
                        @case('rejected') bg-red-100 text-red-800 @break
                    @endswitch
                ">{{ $leaveRequest->status_label }}</span>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-slate-500">الموظف</label>
                    <p class="font-medium text-slate-800">{{ $leaveRequest->employee->full_name }}</p>
                </div>
                <div>
                    <label class="text-sm text-slate-500">القسم</label>
                    <p class="font-medium text-slate-800">{{ $leaveRequest->employee->department?->name ?? 'غير محدد' }}</p>
                </div>
                <div>
                    <label class="text-sm text-slate-500">نوع الإجازة</label>
                    <p class="font-medium text-slate-800">{{ $leaveRequest->leave_type_label }}</p>
                </div>
                <div>
                    <label class="text-sm text-slate-500">عدد الأيام</label>
                    <p class="font-medium text-slate-800">{{ $leaveRequest->days_count }} يوم</p>
                </div>
                <div>
                    <label class="text-sm text-slate-500">من تاريخ</label>
                    <p class="font-medium text-slate-800">{{ $leaveRequest->start_date->format('Y/m/d') }}</p>
                </div>
                <div>
                    <label class="text-sm text-slate-500">إلى تاريخ</label>
                    <p class="font-medium text-slate-800">{{ $leaveRequest->end_date->format('Y/m/d') }}</p>
                </div>
            </div>

            @if($leaveRequest->reason)
            <div class="mt-6 pt-6 border-t border-slate-200">
                <label class="text-sm text-slate-500">السبب</label>
                <p class="text-slate-800 mt-1">{{ $leaveRequest->reason }}</p>
            </div>
            @endif

            @if($leaveRequest->rejection_reason)
            <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-200">
                <label class="text-sm text-red-600 font-medium">سبب الرفض</label>
                <p class="text-red-800 mt-1">{{ $leaveRequest->rejection_reason }}</p>
            </div>
            @endif

            @if($leaveRequest->approved_at)
            <div class="mt-6 pt-6 border-t border-slate-200">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-slate-500">تم البت بواسطة</label>
                        <p class="font-medium text-slate-800">{{ $leaveRequest->approver?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-slate-500">تاريخ البت</label>
                        <p class="font-medium text-slate-800">{{ $leaveRequest->approved_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1">
        @if($leaveRequest->status === 'pending')
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">إجراءات</h3>
            
            <form action="{{ route('admin.leave-requests.approve', $leaveRequest) }}" method="POST" class="mb-4">
                @csrf
                <button type="submit" class="w-full px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition" onclick="return confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')">
                    موافقة
                </button>
            </form>

            <form action="{{ route('admin.leave-requests.reject', $leaveRequest) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">سبب الرفض</label>
                    <textarea name="rejection_reason" rows="3" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                    رفض الطلب
                </button>
            </form>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 {{ $leaveRequest->status === 'pending' ? 'mt-6' : '' }}">
            <h3 class="text-lg font-bold text-slate-800 mb-4">أرصدة الموظف</h3>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-slate-500">الإجازات السنوية</span>
                        <span class="text-sm font-medium text-slate-800">{{ $leaveRequest->employee->annual_leave_balance }} يوم</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-sky-500 h-2 rounded-full" style="width: {{ min(100, ($leaveRequest->employee->annual_leave_balance / 30) * 100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-slate-500">الإجازات المرضية</span>
                        <span class="text-sm font-medium text-slate-800">{{ $leaveRequest->employee->sick_leave_balance }} يوم</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, ($leaveRequest->employee->sick_leave_balance / 15) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
