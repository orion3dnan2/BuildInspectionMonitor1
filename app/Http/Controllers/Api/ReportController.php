<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InspectionReport;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = InspectionReport::with('creator:id,name,username')
            ->search($request->search)
            ->filterByDate($request->date)
            ->filterByOffice($request->office)
            ->latest()
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'record_number' => 'required|string|max:255|unique:inspection_reports,record_number',
            'outgoing_number' => 'nullable|string|max:255',
            'officer_name' => 'required|string|max:255',
            'rank' => 'nullable|string|max:255',
            'office_name' => 'required|string|max:255',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
        ], [
            'record_number.required' => 'رقم السجل مطلوب',
            'record_number.unique' => 'رقم السجل موجود مسبقاً',
            'officer_name.required' => 'اسم الضابط مطلوب',
            'office_name.required' => 'اسم المكتب مطلوب',
            'inspection_date.required' => 'تاريخ التفتيش مطلوب',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $report = InspectionReport::create([
            ...$validator->validated(),
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            'create',
            'InspectionReport',
            $report->id,
            'إنشاء تقرير تفتيش جديد عبر API: ' . $report->record_number,
            null,
            $report->toArray()
        );

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء التقرير بنجاح',
            'data' => $report->load('creator:id,name,username'),
        ], 201);
    }

    public function show(InspectionReport $report)
    {
        return response()->json([
            'success' => true,
            'data' => $report->load('creator:id,name,username'),
        ]);
    }

    public function update(Request $request, InspectionReport $report)
    {
        if (!auth()->user()->isAdmin() && $report->created_by !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا التقرير',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'record_number' => 'required|string|max:255|unique:inspection_reports,record_number,' . $report->id,
            'outgoing_number' => 'nullable|string|max:255',
            'officer_name' => 'required|string|max:255',
            'rank' => 'nullable|string|max:255',
            'office_name' => 'required|string|max:255',
            'inspection_date' => 'required|date',
            'notes' => 'nullable|string',
        ], [
            'record_number.required' => 'رقم السجل مطلوب',
            'record_number.unique' => 'رقم السجل موجود مسبقاً',
            'officer_name.required' => 'اسم الضابط مطلوب',
            'office_name.required' => 'اسم المكتب مطلوب',
            'inspection_date.required' => 'تاريخ التفتيش مطلوب',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $oldValues = $report->toArray();
        $report->update($validator->validated());

        ActivityLog::log(
            'update',
            'InspectionReport',
            $report->id,
            'تعديل تقرير تفتيش عبر API: ' . $report->record_number,
            $oldValues,
            $report->fresh()->toArray()
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث التقرير بنجاح',
            'data' => $report->load('creator:id,name,username'),
        ]);
    }

    public function destroy(InspectionReport $report)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذا التقرير',
            ], 403);
        }

        ActivityLog::log(
            'delete',
            'InspectionReport',
            $report->id,
            'حذف تقرير تفتيش عبر API: ' . $report->record_number,
            $report->toArray(),
            null
        );

        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف التقرير بنجاح',
        ]);
    }
}
