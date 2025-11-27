<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with('manager');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->orderBy('name')->paginate(10);

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $users = User::where('role', 'admin')->orWhere('role', 'supervisor')->orderBy('name')->get();
        return view('admin.departments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم إضافة القسم بنجاح');
    }

    public function show(Department $department)
    {
        $department->load(['manager', 'employees']);
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $users = User::where('role', 'admin')->orWhere('role', 'supervisor')->orderBy('name')->get();
        return view('admin.departments.edit', compact('department', 'users'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'لا يمكن حذف القسم لوجود موظفين مرتبطين به');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }
}
