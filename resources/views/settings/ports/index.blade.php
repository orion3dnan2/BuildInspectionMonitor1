@extends('layouts.app')

@section('title', 'إدارة المنافذ - نظام الرقابة والتفتيش')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm mb-4 bg-white px-3 py-1.5 rounded-lg border border-gray-200">
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            العودة الرئيسية
        </a>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">الإعدادات</h1>
                <p class="text-gray-500 text-sm">إدارة إعدادات النظام والبيانات الأساسية</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="flex border-b border-gray-200">
            <a href="{{ route('settings.ports.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-gray-700 bg-white border-b-2 border-blue-600 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                المنافذ
            </a>
            <a href="{{ route('settings.stations.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition border-b-2 border-transparent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                المخافر
            </a>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('settings.users.index') }}" class="flex-1 flex items-center justify-center gap-2 px-6 py-4 text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition border-b-2 border-transparent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                المستخدمون
            </a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">البحث والإضافة</h2>
        <div class="flex gap-4">
            <div class="flex-1 relative">
                <input type="text" id="searchPorts" placeholder="ابحث عن منفذ..."
                    class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة منفذ
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">المنافذ</h2>
            <span class="text-sm text-gray-500">إجمالي المنافذ: {{ $ports->total() }}</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-medium text-gray-600">الإجراءات</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600">النوع</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600">اسم المنفذ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" id="portsTable">
                    @forelse($ports as $port)
                    <tr class="hover:bg-gray-50 port-row" data-name="{{ strtolower($port->name) }}">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <form action="{{ route('settings.ports.destroy', $port) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 hover:bg-red-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                                <button onclick="editPort({{ $port->id }}, '{{ $port->name }}', '{{ $port->type }}')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs flex items-center gap-1 hover:bg-gray-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    تعديل
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $port->type ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $port->name }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center text-gray-500">لا توجد منافذ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ports->hasPages())
        <div class="mt-4 pt-4 border-t border-gray-100">
            {{ $ports->links() }}
        </div>
        @endif
    </div>
</div>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 id="modal-title" class="text-xl font-bold text-gray-800 mb-4">إضافة منفذ</h3>
        <form id="port-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المنفذ <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="port-name" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                <select name="type" id="port-type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">اختر النوع</option>
                    <option value="بري">بري</option>
                    <option value="بحري">بحري</option>
                    <option value="جوي">جوي</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition">
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
    document.getElementById('modal-title').textContent = 'إضافة منفذ';
    document.getElementById('port-form').action = '{{ route('settings.ports.store') }}';
    document.getElementById('form-method').value = 'POST';
    document.getElementById('port-name').value = '';
    document.getElementById('port-type').value = '';
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function editPort(id, name, type) {
    document.getElementById('modal-title').textContent = 'تعديل المنفذ';
    document.getElementById('port-form').action = '/settings/ports/' + id;
    document.getElementById('form-method').value = 'PUT';
    document.getElementById('port-name').value = name;
    document.getElementById('port-type').value = type || '';
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

document.getElementById('searchPorts').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.port-row').forEach(function(row) {
        const name = row.dataset.name;
        if (name.includes(search)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
