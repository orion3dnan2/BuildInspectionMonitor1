@extends('layouts.app')

@section('title', 'طلبات الإجازات')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">طلبات الإجازات</h1>
            <p class="text-slate-500 mt-1">إدارة طلبات الإجازات والموافقات</p>
        </div>
        <a href="{{ route('admin.leave-requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            طلب إجازة جديد
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 border-b border-slate-100">
        <form action="{{ route('admin.leave-requests.index') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="status" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الحالات</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
            </select>
            <select name="leave_type" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الأنواع</option>
                <option value="annual" {{ request('leave_type') == 'annual' ? 'selected' : '' }}>سنوية</option>
                <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>مرضية</option>
                <option value="emergency" {{ request('leave_type') == 'emergency' ? 'selected' : '' }}>طارئة</option>
                <option value="unpaid" {{ request('leave_type') == 'unpaid' ? 'selected' : '' }}>بدون راتب</option>
            </select>
            <select name="department_id" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                <option value="">جميع الأقسام</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
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
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">نوع الإجازة</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">من</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">إلى</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الأيام</th>
                <th class="text-right px-6 py-3 text-sm font-medium text-slate-500">الحالة</th>
                <th class="text-center px-6 py-3 text-sm font-medium text-slate-500">الإجراءات</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($leaveRequests as $leave)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-4 font-medium text-slate-800">{{ $leave->employee->full_name }}</td>
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
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.leave-requests.show', $leave) }}" class="p-2 text-slate-400 hover:text-sky-600 transition" title="عرض">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        @if($leave->status === 'pending')
                        <form action="{{ route('admin.leave-requests.approve', $leave) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-emerald-600 transition" title="موافقة" onclick="return confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </form>
                        <button type="button" onclick="showRejectModal({{ $leave->id }})" class="p-2 text-slate-400 hover:text-red-600 transition" title="رفض">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                    لا توجد طلبات إجازات
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($leaveRequests->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $leaveRequests->links() }}
    </div>
    @endif
</div>

<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-bold text-slate-800 mb-4">سبب الرفض</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="rejection_reason" rows="4" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 mb-4" placeholder="اذكر سبب رفض الطلب..."></textarea>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">رفض الطلب</button>
                <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">إلغاء</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showRejectModal(id) {
    document.getElementById('rejectForm').action = '/admin/leave-requests/' + id + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}
</script>
@endpush
@endsection
