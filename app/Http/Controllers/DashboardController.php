<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRecords = Record::count();
        $todayRecords = Record::whereDate('created_at', today())->count();
        $monthRecords = Record::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $yearRecords = Record::whereYear('created_at', Carbon::now()->year)->count();
        
        $recentRecords = Record::with('creator')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRecords',
            'todayRecords',
            'monthRecords',
            'yearRecords',
            'recentRecords'
        ));
    }
}
