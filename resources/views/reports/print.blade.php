<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير السجلات - نظام الرقابة والتفتيش</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="no-print mb-6">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
            طباعة التقرير
        </button>
        <a href="{{ route('reports.index', request()->all()) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg mr-2">
            رجوع
        </a>
    </div>

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">تقرير سجلات الرقابة والتفتيش</h1>
        <p class="text-gray-600">تاريخ التقرير: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <div class="mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-2">ملخص:</h2>
        <p>إجمالي السجلات: {{ $records->count() }}</p>
    </div>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-4 py-2 text-right">رقم الصادر</th>
                <th class="border border-gray-300 px-4 py-2 text-right">الرقم العسكري</th>
                <th class="border border-gray-300 px-4 py-2 text-right">الاسم</th>
                <th class="border border-gray-300 px-4 py-2 text-right">الرتبة</th>
                <th class="border border-gray-300 px-4 py-2 text-right">المحافظة</th>
                <th class="border border-gray-300 px-4 py-2 text-right">تاريخ الجولة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $record->record_number }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $record->military_id }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $record->full_name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $record->rank ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $record->governorate ?? '-' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $record->round_date->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">لا توجد سجلات</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
