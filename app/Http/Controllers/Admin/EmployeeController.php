<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with('department');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->orderBy('first_name')->paginate(10);
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $users = User::whereDoesntHave('employee')->orderBy('name')->get();
        return view('admin.employees.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number',
            'first_name' => 'required|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'third_name' => 'nullable|string|max:100',
            'fourth_name' => 'nullable|string|max:100',
            'civil_id' => 'required|string|max:20|unique:employees,civil_id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'job_title' => 'nullable|string|max:100',
            'rank' => 'nullable|string|max:50',
            'hire_date' => 'nullable|date',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'address' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'annual_leave_balance' => 'nullable|integer|min:0',
            'sick_leave_balance' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id',
            'notes' => 'nullable|string',
        ]);

        Employee::create($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم إضافة الموظف بنجاح');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'user', 'attendances' => function ($q) {
            $q->orderBy('date', 'desc')->limit(30);
        }, 'leaveRequests' => function ($q) {
            $q->orderBy('created_at', 'desc')->limit(10);
        }]);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $users = User::whereDoesntHave('employee')
            ->orWhere('id', $employee->user_id)
            ->orderBy('name')
            ->get();
        return view('admin.employees.edit', compact('employee', 'departments', 'users'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number,' . $employee->id,
            'first_name' => 'required|string|max:100',
            'second_name' => 'nullable|string|max:100',
            'third_name' => 'nullable|string|max:100',
            'fourth_name' => 'nullable|string|max:100',
            'civil_id' => 'required|string|max:20|unique:employees,civil_id,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'job_title' => 'nullable|string|max:100',
            'rank' => 'nullable|string|max:50',
            'hire_date' => 'nullable|date',
            'birth_date' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'address' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'annual_leave_balance' => 'nullable|integer|min:0',
            'sick_leave_balance' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,on_leave,terminated',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id,' . $employee->id,
            'notes' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }
}
