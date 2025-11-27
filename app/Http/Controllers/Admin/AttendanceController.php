<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['employee.department']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(10);
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.attendances.index', compact('attendances', 'departments'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.attendances.create', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,leave,holiday',
            'notes' => 'nullable|string',
        ]);

        $exists = Attendance::where('employee_id', $validated['employee_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['employee_id' => 'يوجد سجل حضور لهذا الموظف في هذا التاريخ']);
        }

        Attendance::create($validated);

        return redirect()->route('admin.attendances.index')
            ->with('success', 'تم تسجيل الحضور بنجاح');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('employee.department');
        return view('admin.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('status', 'active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        return view('admin.attendances.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,leave,holiday',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return redirect()->route('admin.attendances.index')
            ->with('success', 'تم تحديث سجل الحضور بنجاح');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendances.index')
            ->with('success', 'تم حذف سجل الحضور بنجاح');
    }

    public function bulkCreate()
    {
        $employees = Employee::where('status', 'active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        return view('admin.attendances.bulk-create', compact('employees', 'departments'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.status' => 'required|in:present,absent,late,leave,holiday',
            'attendances.*.check_in' => 'nullable|date_format:H:i',
            'attendances.*.check_out' => 'nullable|date_format:H:i',
        ]);

        foreach ($validated['attendances'] as $attendance) {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $attendance['employee_id'],
                    'date' => $validated['date'],
                ],
                [
                    'status' => $attendance['status'],
                    'check_in' => $attendance['check_in'] ?? null,
                    'check_out' => $attendance['check_out'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.attendances.index')
            ->with('success', 'تم تسجيل الحضور الجماعي بنجاح');
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $query = Employee::with(['attendances' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('date', [$startDate, $endDate]);
        }])->where('status', 'active');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.attendances.report', compact('employees', 'departments', 'startDate', 'endDate'));
    }
}
