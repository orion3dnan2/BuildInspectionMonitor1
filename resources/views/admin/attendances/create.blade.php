@extends('layouts.app')

@section('title', 'تسجيل حضور')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة لسجل الحضور
    </a>
    <h1 class="text-2xl font-bold text-slate-800">تسجيل حضور موظف</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.attendances.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الموظف <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('employee_id') border-red-500 @enderror">
                    <option value="">-- اختر الموظف --</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }} ({{ $employee->department?->name ?? 'بدون قسم' }})</option>
                    @endforeach
                </select>
                @error('employee_id')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">التاريخ <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ old('date', today()->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">وقت الحضور</label>
                <input type="time" name="check_in" value="{{ old('check_in') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">وقت الانصراف</label>
                <input type="time" name="check_out" value="{{ old('check_out') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الحالة <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>حاضر</option>
                    <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>غائب</option>
                    <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>متأخر</option>
                    <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>إجازة</option>
                    <option value="holiday" {{ old('status') == 'holiday' ? 'selected' : '' }}>عطلة</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                حفظ
            </button>
            <a href="{{ route('admin.attendances.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
