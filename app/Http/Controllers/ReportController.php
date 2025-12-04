<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Station;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $stats = $this->getStatistics();
        
        $records = Record::query()
            ->when($request->period, function ($query, $period) use ($request) {
                switch ($period) {
                    case 'today':
                        $query->whereDate('round_date', today());
                        break;
                    case 'month':
                        $query->whereMonth('round_date', Carbon::now()->month)
                              ->whereYear('round_date', Carbon::now()->year);
                        break;
                    case 'year':
                        $query->whereYear('round_date', Carbon::now()->year);
                        break;
                    case 'custom':
                        if ($request->date_from && $request->date_to) {
                            $query->whereBetween('round_date', [$request->date_from, $request->date_to]);
                        }
                        break;
                }
            })
            ->when($request->governorate, function ($query, $value) {
                $query->where('governorate', $value);
            })
            ->when($request->station, function ($query, $value) {
                $query->where('station', $value);
            })
            ->when($request->rank, function ($query, $value) {
                $query->where('rank', $value);
            })
            ->when($request->action_type, function ($query, $value) {
                $query->where('action_type', $value);
            })
            ->when($request->name, function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->where('first_name', 'like', "%{$value}%")
                      ->orWhere('second_name', 'like', "%{$value}%")
                      ->orWhere('third_name', 'like', "%{$value}%")
                      ->orWhere('fourth_name', 'like', "%{$value}%");
                });
            })
            ->latest('round_date')
            ->paginate(20)
            ->withQueryString();

        $stations = Station::orderBy('name')->get();
        $governorates = $this->getGovernorates();
        $ranks = $this->getRanks();
        $actionTypes = $this->getActionTypes();

        return view('reports.index', compact('stats', 'records', 'stations', 'governorates', 'ranks', 'actionTypes'));
    }

    public function print(Request $request)
    {
        $records = Record::query()
            ->when($request->period, function ($query, $period) use ($request) {
                switch ($period) {
                    case 'today':
                        $query->whereDate('round_date', today());
                        break;
                    case 'month':
                        $query->whereMonth('round_date', Carbon::now()->month)
                              ->whereYear('round_date', Carbon::now()->year);
                        break;
                    case 'year':
                        $query->whereYear('round_date', Carbon::now()->year);
                        break;
                    case 'custom':
                        if ($request->date_from && $request->date_to) {
                            $query->whereBetween('round_date', [$request->date_from, $request->date_to]);
                        }
                        break;
                }
            })
            ->when($request->governorate, function ($query, $value) {
                $query->where('governorate', $value);
            })
            ->when($request->station, function ($query, $value) {
                $query->where('station', $value);
            })
            ->when($request->rank, function ($query, $value) {
                $query->where('rank', $value);
            })
            ->when($request->action_type, function ($query, $value) {
                $query->where('action_type', $value);
            })
            ->when($request->name, function ($query, $value) {
                $query->where(function ($q) use ($value) {
                    $q->where('first_name', 'like', "%{$value}%")
                      ->orWhere('second_name', 'like', "%{$value}%")
                      ->orWhere('third_name', 'like', "%{$value}%")
                      ->orWhere('fourth_name', 'like', "%{$value}%");
                });
            })
            ->latest('round_date')
            ->get();

        return view('reports.print', compact('records'));
    }

    private function getStatistics(): array
    {
        return [
            'total' => Record::count(),
            'today' => Record::whereDate('round_date', today())->count(),
            'month' => Record::whereMonth('round_date', Carbon::now()->month)
                             ->whereYear('round_date', Carbon::now()->year)
                             ->count(),
            'year' => Record::whereYear('round_date', Carbon::now()->year)->count(),
        ];
    }

    private function getGovernorates(): array
    {
        return config('system.governorates', []);
    }

    private function getRanks(): array
    {
        return config('system.ranks', []);
    }

    private function getActionTypes(): array
    {
        return config('system.action_types', []);
    }
}
