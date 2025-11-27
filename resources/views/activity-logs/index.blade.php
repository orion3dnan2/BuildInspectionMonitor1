@extends('layouts.app')

@section('title', 'سجل النشاط - نظام التفتيش والمراقبة')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">سجل النشاط</h1>
    <p class="text-gray-600 mt-1">تتبع جميع العمليات في النظام</p>
</div>

<div class="bg-white rounded-xl shadow-sm mb-6">
    <form method="GET" action="{{ route('activity-logs.index') }}" class="p-4 flex flex-wrap gap-4">
        <div class="w-48">
            <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">جميع الإجراءات</option>
                <option value="create" {{ request('action') === 'create' ? 'selected' : '' }}>إنشاء</option>
                <option value="update" {{ request('action') === 'update' ? 'selected' : '' }}>تعديل</option>
                <option value="delete" {{ request('action') === 'delete' ? 'selected' : '' }}>حذف</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">تصفية</button>
        <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">مسح</a>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراء</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">النوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الوصف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->user->name ?? 'غير معروف' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $actionColors = [
                                'create' => 'bg-green-100 text-green-800',
                                'update' => 'bg-yellow-100 text-yellow-800',
                                'delete' => 'bg-red-100 text-red-800',
                            ];
                            $actionLabels = [
                                'create' => 'إنشاء',
                                'update' => 'تعديل',
                                'delete' => 'حذف',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $actionLabels[$log->action] ?? $log->action }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $log->model_type === 'InspectionReport' ? 'تقرير' : ($log->model_type === 'User' ? 'مستخدم' : $log->model_type) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $log->description }}">{{ $log->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->ip_address ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">لا توجد سجلات نشاط</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
