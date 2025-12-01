@extends('layouts.app')

@section('title', 'إضافة دور جديد')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">إضافة دور جديد</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">إنشاء دور جديد وتحديد صلاحياته</p>
        </div>
        <a href="{{ route('settings.permissions.roles') }}" class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
            </svg>
            رجوع
        </a>
    </div>

    <form action="{{ route('settings.permissions.store-role') }}" method="POST">
        @csrf
        
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4">معلومات الدور</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">اسم الدور <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="مثال: محرر المحتوى" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">المعرف (Slug) <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="مثال: content_editor" required>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">الوصف</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="وصف مختصر للدور وصلاحياته">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white">الصلاحيات</h2>
                <div class="flex gap-2">
                    <button type="button" onclick="selectAll()" class="px-3 py-1.5 text-sm bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">تحديد الكل</button>
                    <button type="button" onclick="deselectAll()" class="px-3 py-1.5 text-sm bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition">إلغاء التحديد</button>
                </div>
            </div>
            
            <div class="space-y-6">
                @foreach($groupedPermissions as $module => $modulePermissions)
                <div class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
                    <div class="bg-slate-50 dark:bg-slate-700/50 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="module_{{ $module }}" onchange="toggleModule('{{ $module }}')" class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-sky-500 focus:ring-sky-500">
                            <label for="module_{{ $module }}" class="font-medium text-slate-700 dark:text-slate-200">{{ $modules[$module] ?? $module }}</label>
                        </div>
                        <span class="text-sm text-slate-500 dark:text-slate-400">{{ $modulePermissions->count() }} صلاحية</span>
                    </div>
                    <div class="p-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($modulePermissions as $permission)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" data-module="{{ $module }}" {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-sky-500 focus:ring-sky-500 permission-checkbox">
                            <span class="text-sm text-slate-600 dark:text-slate-300">{{ explode(' ', $permission->name)[0] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('settings.permissions.roles') }}" class="px-6 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">إلغاء</a>
            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition">حفظ الدور</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function selectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
    document.querySelectorAll('[id^="module_"]').forEach(cb => cb.checked = true);
}

function deselectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('[id^="module_"]').forEach(cb => cb.checked = false);
}

function toggleModule(module) {
    const moduleCheckbox = document.getElementById('module_' + module);
    const permissionCheckboxes = document.querySelectorAll(`[data-module="${module}"]`);
    permissionCheckboxes.forEach(cb => cb.checked = moduleCheckbox.checked);
}

document.querySelectorAll('.permission-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const module = this.dataset.module;
        const moduleCheckboxes = document.querySelectorAll(`[data-module="${module}"]`);
        const allChecked = Array.from(moduleCheckboxes).every(c => c.checked);
        document.getElementById('module_' + module).checked = allChecked;
    });
});
</script>
@endpush
@endsection
