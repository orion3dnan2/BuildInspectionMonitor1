<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee.department', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        if ($request->filled('department_id')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(10);
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.leave-requests.index', compact('leaveRequests', 'departments'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        return view('admin.leave-requests.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:annual,sick,emergency,unpaid,maternity,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['days_count'] = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create($validated);

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم تقديم طلب الإجازة بنجاح');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee.department', 'approver']);
        return view('admin.leave-requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('admin.leave-requests.index')
                ->with('error', 'لا يمكن تعديل طلب تم البت فيه');
        }

        $employees = Employee::where('status', 'active')
            ->with('department')
            ->orderBy('first_name')
            ->get();
        return view('admin.leave-requests.edit', compact('leaveRequest', 'employees'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('admin.leave-requests.index')
                ->with('error', 'لا يمكن تعديل طلب تم البت فيه');
        }

        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,emergency,unpaid,maternity,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['days_count'] = $startDate->diffInDays($endDate) + 1;

        $leaveRequest->update($validated);

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم تحديث طلب الإجازة بنجاح');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('admin.leave-requests.index')
                ->with('error', 'لا يمكن حذف طلب تم البت فيه');
        }

        $leaveRequest->delete();

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم حذف طلب الإجازة بنجاح');
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('admin.leave-requests.index')
                ->with('error', 'هذا الطلب تم البت فيه مسبقاً');
        }

        $employee = $leaveRequest->employee;
        
        if ($leaveRequest->leave_type === 'annual') {
            if ($employee->annual_leave_balance < $leaveRequest->days_count) {
                return redirect()->route('admin.leave-requests.index')
                    ->with('error', 'رصيد الإجازات السنوية غير كافٍ');
            }
            $employee->decrement('annual_leave_balance', $leaveRequest->days_count);
        } elseif ($leaveRequest->leave_type === 'sick') {
            if ($employee->sick_leave_balance < $leaveRequest->days_count) {
                return redirect()->route('admin.leave-requests.index')
                    ->with('error', 'رصيد الإجازات المرضية غير كافٍ');
            }
            $employee->decrement('sick_leave_balance', $leaveRequest->days_count);
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم الموافقة على طلب الإجازة');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return redirect()->route('admin.leave-requests.index')
                ->with('error', 'هذا الطلب تم البت فيه مسبقاً');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.leave-requests.index')
            ->with('success', 'تم رفض طلب الإجازة');
    }
}
