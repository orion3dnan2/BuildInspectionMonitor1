@extends('layouts.app')

@section('title', 'إدارة المخافر - نظام الرقابة والتفتيش')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">إدارة المخافر</h1>
        <p class="text-gray-600">إدارة مخافر النظام</p>
    </div>
    <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        إضافة مخفر
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">اسم المخفر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المحافظة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الإنشاء</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($stations as $station)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $station->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $station->governorate ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $station->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center gap-2">
                            <button onclick="editStation({{ $station->id }}, '{{ $station->name }}', '{{ $station->governorate }}')" class="text-yellow-600 hover:text-yellow-800">تعديل</button>
                            <form action="{{ route('settings.stations.destroy', $station) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">لا توجد مخافر</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stations->hasPages())
    <div class="p-4 border-t border-gray-200">
        {{ $stations->links() }}
    </div>
    @endif
</div>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 id="modal-title" class="text-xl font-bold text-gray-800 mb-4">إضافة مخفر</h3>
        <form id="station-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المخفر <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="station-name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="governorate" class="block text-sm font-medium text-gray-700 mb-2">المحافظة</label>
                <select name="governorate" id="station-governorate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر المحافظة</option>
                    @foreach($governorates as $gov)
                        <option value="{{ $gov }}">{{ $gov }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    حفظ
                </button>
                <button type="button" onclick="closeModal()" class="text-gray-600 hover:text-gray-800">إلغاء</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal() {
    document.getElementById('modal-title').textContent = 'إضافة مخفر';
    document.getElementById('station-form').action = '{{ route('settings.stations.store') }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('station-name').value = '';
    document.getElementById('station-governorate').value = '';
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function editStation(id, name, governorate) {
    document.getElementById('modal-title').textContent = 'تعديل المخفر';
    document.getElementById('station-form').action = '/settings/stations/' + id;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('station-name').value = name;
    document.getElementById('station-governorate').value = governorate || '';
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
@endsection
