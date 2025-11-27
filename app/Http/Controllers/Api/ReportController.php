<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Record;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Record::query();

        if ($request->has('from_date')) {
            $query->whereDate('round_date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('round_date', '<=', $request->to_date);
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($records);
    }

    public function show(Record $report)
    {
        return response()->json($report);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'غير مسموح'], 403);
    }

    public function update(Request $request, Record $report)
    {
        return response()->json(['message' => 'غير مسموح'], 403);
    }

    public function destroy(Record $report)
    {
        return response()->json(['message' => 'غير مسموح'], 403);
    }
}
