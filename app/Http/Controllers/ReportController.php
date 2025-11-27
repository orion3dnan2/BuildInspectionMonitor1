<?php

namespace App\Http\Controllers;

use App\Models\InspectionReport;
use App\Models\ActivityLog;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = InspectionReport::with('creator')
            ->search($request->search)
            ->filterByDate($request->date)
            ->filterByOffice($request->office)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(StoreReportRequest $request)
    {
        $report = InspectionReport::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);

        ActivityLog::log(
            'create',
            'InspectionReport',
            $report->id,
            'إنشاء تقرير تفتيش جديد: ' . $report->record_number,
            null,
            $report->toArray()
        );

        return redirect()
            ->route('reports.index')
            ->with('success', 'تم إنشاء التقرير بنجاح');
    }

    public function show(InspectionReport $report)
    {
        $report->load('creator');
        return view('reports.show', compact('report'));
    }

    public function edit(InspectionReport $report)
    {
        $this->authorize('update', $report);
        return view('reports.edit', compact('report'));
    }

    public function update(UpdateReportRequest $request, InspectionReport $report)
    {
        $this->authorize('update', $report);

        $oldValues = $report->toArray();
        $report->update($request->validated());

        ActivityLog::log(
            'update',
            'InspectionReport',
            $report->id,
            'تعديل تقرير تفتيش: ' . $report->record_number,
            $oldValues,
            $report->fresh()->toArray()
        );

        return redirect()
            ->route('reports.index')
            ->with('success', 'تم تحديث التقرير بنجاح');
    }

    public function destroy(InspectionReport $report)
    {
        $this->authorize('delete', $report);

        ActivityLog::log(
            'delete',
            'InspectionReport',
            $report->id,
            'حذف تقرير تفتيش: ' . $report->record_number,
            $report->toArray(),
            null
        );

        $report->delete();

        return redirect()
            ->route('reports.index')
            ->with('success', 'تم حذف التقرير بنجاح');
    }

    public function print(InspectionReport $report)
    {
        $report->load('creator');
        return view('reports.print', compact('report'));
    }
}
