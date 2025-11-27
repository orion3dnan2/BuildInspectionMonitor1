@extends('layouts.app')

@section('title', 'استيراد البيانات - نظام الرقابة والتفتيش')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">استيراد البيانات</h1>
    <p class="text-gray-600">استيراد سجلات من ملف Excel</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-800 mb-4">رفع ملف Excel</h3>
    
    @if(session('import_results'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        <h4 class="font-bold mb-2">نتائج الاستيراد:</h4>
        <p>تم استيراد {{ session('import_results')['success'] }} سجل بنجاح</p>
        @if(session('import_results')['failed'] > 0)
            <p class="text-red-600">فشل استيراد {{ session('import_results')['failed'] }} سجل</p>
        @endif
    </div>
    @endif

    <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">اختر ملف Excel</label>
            <input type="file" name="file" id="file" accept=".xlsx,.xls" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('file') border-red-500 @enderror">
            @error('file')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
            <p class="mt-2 text-sm text-gray-500">الملفات المدعومة: .xlsx, .xls</p>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
            استيراد البيانات
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-medium text-gray-800 mb-4">تنسيق ملف Excel المطلوب</h3>
    <p class="text-gray-600 mb-4">يجب أن يحتوي ملف Excel على الأعمدة التالية بالترتيب:</p>
    
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-right">رقم الصادر</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">الرقم العسكري</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">الاسم الأول</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">الاسم الثاني</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">الاسم الثالث</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">الاسم الرابع</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">الرتبة</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">المحافظة</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">المخفر</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">نوع الإجراء</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">المنافذ</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">تاريخ الجولة</th>
                    <th class="border border-gray-300 px-4 py-2 text-right">ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">001/2025</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">123456</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">محمد</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">أحمد</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">علي</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">الكويتي</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">ملازم</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">العاصمة</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">مخفر الصالحية</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">تفتيش دوري</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">منفذ العبدلي</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">2025-01-15</td>
                    <td class="border border-gray-300 px-4 py-2 text-gray-500">ملاحظات</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('import.template') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-block">
            تحميل قالب Excel
        </a>
    </div>
</div>
@endsection
