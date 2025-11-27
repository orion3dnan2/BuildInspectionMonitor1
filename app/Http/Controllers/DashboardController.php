<?php

namespace App\Http\Controllers;

use App\Models\InspectionReport;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = InspectionReport::count();
        $todayReports = InspectionReport::whereDate('created_at', today())->count();
        $totalInspectors = User::where('role', 'inspector')->count();
        $recentReports = InspectionReport::with('creator')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalReports',
            'todayReports',
            'totalInspectors',
            'recentReports'
        ));
    }
}
