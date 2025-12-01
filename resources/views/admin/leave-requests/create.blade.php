@extends('layouts.app')

@section('title', 'طلب إجازة جديد')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.leave-requests.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة لطلبات الإجازات
    </a>
    <h1 class="text-2xl font-bold text-slate-800">طلب إجازة جديد</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.leave-requests.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الموظف <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('employee_id') border-red-500 @enderror">
                    <option value="">-- اختر الموظف --</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->full_name }} 
                        (سنوية: {{ $employee->annual_leave_balance }} | مرضية: {{ $employee->sick_leave_balance }})
                    </option>
                    @endforeach
                </select>
                @error('employee_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">نوع الإجازة <span class="text-red-500">*</span></label>
                <select name="leave_type" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="annual" {{ old('leave_type') == 'annual' ? 'selected' : '' }}>سنوية</option>
                    <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>مرضية</option>
                    <option value="emergency" {{ old('leave_type') == 'emergency' ? 'selected' : '' }}>طارئة</option>
                    <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>بدون راتب</option>
                    <option value="maternity" {{ old('leave_type') == 'maternity' ? 'selected' : '' }}>أمومة</option>
                    <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>أخرى</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">من تاريخ <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">إلى تاريخ <span class="text-red-500">*</span></label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">السبب</label>
                <textarea name="reason" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">{{ old('reason') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                تقديم الطلب
            </button>
            <a href="{{ route('admin.leave-requests.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
