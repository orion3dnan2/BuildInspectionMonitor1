@extends('layouts.app')

@section('title', 'التقارير - نظام التفتيش والمراقبة')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">التقارير</h1>
        <p class="text-gray-600 mt-1">إدارة تقارير التفتيش</p>
    </div>
    <a href="{{ route('reports.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        إضافة تقرير
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <input type="text" name="office" value="{{ request('office') }}" placeholder="المكتب" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <input type="date" name="date" value="{{ request('date') }}" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">بحث</button>
            <a href="{{ route('reports.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">مسح</a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم السجل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الصادر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم الضابط</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرتبة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المكتب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التفتيش</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->record_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->outgoing_number ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->officer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->rank ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->office_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->inspection_date->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('reports.show', $report) }}" class="text-blue-600 hover:text-blue-800" title="عرض">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @can('update', $report)
                            <a href="{{ route('reports.edit', $report) }}" class="text-yellow-600 hover:text-yellow-800" title="تعديل">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @endcan
                            <a href="{{ route('reports.print', $report) }}" class="text-green-600 hover:text-green-800" title="طباعة" target="_blank">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                            </a>
                            @can('delete', $report)
                            <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">لا توجد تقارير</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reports->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection
