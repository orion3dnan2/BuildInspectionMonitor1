<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($request->user_id, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('activity-logs.index', compact('logs'));
    }
}
