@extends('layouts.app')

@section('title', 'إضافة موظف جديد')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition mb-4">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        العودة للموظفين
    </a>
    <h1 class="text-2xl font-bold text-slate-800">إضافة موظف جديد</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.employees.store') }}" method="POST">
        @csrf

        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">البيانات الأساسية</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الرقم الوظيفي <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_number" value="{{ old('employee_number') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('employee_number') border-red-500 @enderror">
                    @error('employee_number')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الرقم المدني <span class="text-red-500">*</span></label>
                    <input type="text" name="civil_id" value="{{ old('civil_id') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('civil_id') border-red-500 @enderror">
                    @error('civil_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                        <option value="terminated" {{ old('status') == 'terminated' ? 'selected' : '' }}>منتهي الخدمة</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">الاسم الرباعي</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الأول <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 @error('first_name') border-red-500 @enderror">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الثاني</label>
                    <input type="text" name="second_name" value="{{ old('second_name') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الثالث</label>
                    <input type="text" name="third_name" value="{{ old('third_name') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الرابع</label>
                    <input type="text" name="fourth_name" value="{{ old('fourth_name') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">بيانات العمل</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">القسم</label>
                    <select name="department_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- اختر القسم --</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">المسمى الوظيفي</label>
                    <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الرتبة</label>
                    <input type="text" name="rank" value="{{ old('rank') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ التعيين</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الراتب</label>
                    <input type="number" step="0.001" name="salary" value="{{ old('salary') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">حساب مستخدم</label>
                    <select name="user_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">-- بدون حساب --</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">البيانات الشخصية</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الجنس <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الحالة الاجتماعية <span class="text-red-500">*</span></label>
                    <select name="marital_status" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>أعزب</option>
                        <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>متزوج</option>
                        <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>مطلق</option>
                        <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>أرمل</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ الميلاد</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">العنوان</label>
                    <input type="text" name="address" value="{{ old('address') }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-200">أرصدة الإجازات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">رصيد الإجازات السنوية</label>
                    <input type="number" name="annual_leave_balance" value="{{ old('annual_leave_balance', 30) }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">رصيد الإجازات المرضية</label>
                    <input type="number" name="sick_leave_balance" value="{{ old('sick_leave_balance', 15) }}" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center gap-4 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition">
                حفظ الموظف
            </button>
            <a href="{{ route('admin.employees.index') }}" class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
