<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة تقرير - {{ $report->record_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="no-print fixed top-4 left-4">
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            طباعة
        </button>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8 border-b-2 border-gray-300 pb-6">
            <h1 class="text-2xl font-bold text-gray-800">نظام التفتيش والمراقبة</h1>
            <h2 class="text-xl font-semibold text-gray-600 mt-2">تقرير تفتيش</h2>
        </div>

        <div class="border border-gray-300 rounded-lg overflow-hidden">
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold w-1/3">رقم السجل</td>
                        <td class="px-4 py-3">{{ $report->record_number }}</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold">رقم الصادر</td>
                        <td class="px-4 py-3">{{ $report->outgoing_number ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold">اسم الضابط</td>
                        <td class="px-4 py-3">{{ $report->officer_name }}</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold">الرتبة</td>
                        <td class="px-4 py-3">{{ $report->rank ?? '-' }}</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold">اسم المكتب</td>
                        <td class="px-4 py-3">{{ $report->office_name }}</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold">تاريخ التفتيش</td>
                        <td class="px-4 py-3">{{ $report->inspection_date->format('Y-m-d') }}</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="bg-gray-100 px-4 py-3 font-semibold">تم الإنشاء بواسطة</td>
                        <td class="px-4 py-3">{{ $report->creator->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-gray-100 px-4 py-3 font-semibold align-top">ملاحظات</td>
                        <td class="px-4 py-3 whitespace-pre-line">{{ $report->notes ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>تم الطباعة بتاريخ: {{ now()->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</body>
</html>
