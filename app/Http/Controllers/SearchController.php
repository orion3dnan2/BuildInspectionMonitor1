<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Station;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $records = null;
        $searched = false;

        if ($request->hasAny(['search', 'record_number', 'military_id', 'name', 'governorate', 'rank', 'station', 'action_type', 'date_from', 'date_to'])) {
            $searched = true;
            
            $records = Record::with('creator')
                ->when($request->search, function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('record_number', 'like', "%{$value}%")
                          ->orWhere('military_id', 'like', "%{$value}%")
                          ->orWhere('first_name', 'like', "%{$value}%")
                          ->orWhere('second_name', 'like', "%{$value}%")
                          ->orWhere('third_name', 'like', "%{$value}%")
                          ->orWhere('fourth_name', 'like', "%{$value}%");
                    });
                })
                ->when($request->record_number, function ($query, $value) {
                    $query->where('record_number', 'like', "%{$value}%");
                })
                ->when($request->military_id, function ($query, $value) {
                    $query->where('military_id', 'like', "%{$value}%");
                })
                ->when($request->name, function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('first_name', 'like', "%{$value}%")
                          ->orWhere('second_name', 'like', "%{$value}%")
                          ->orWhere('third_name', 'like', "%{$value}%")
                          ->orWhere('fourth_name', 'like', "%{$value}%");
                    });
                })
                ->when($request->action_type, function ($query, $value) {
                    $query->where('action_type', 'like', "%{$value}%");
                })
                ->filterByGovernorate($request->governorate)
                ->filterByRank($request->rank)
                ->filterByStation($request->station)
                ->filterByDateRange($request->date_from, $request->date_to)
                ->latest()
                ->paginate(15)
                ->withQueryString();
        }

        $stations = Station::orderBy('name')->get();
        $governorates = $this->getGovernorates();
        $ranks = $this->getRanks();

        return view('search.index', compact('records', 'searched', 'stations', 'governorates', 'ranks'));
    }

    public function show(Record $record)
    {
        return view('search.show', compact('record'));
    }

    private function getGovernorates(): array
    {
        return [
            'العاصمة', 'حولي', 'الفروانية', 'الجهراء', 'الأحمدي', 'مبارك الكبير'
        ];
    }

    private function getRanks(): array
    {
        return [
            'ملازم', 'ملازم أول', 'نقيب', 'رائد', 'مقدم', 'عقيد', 'عميد', 'لواء'
        ];
    }
}
