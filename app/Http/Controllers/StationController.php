<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Log;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index(Request $request)
    {
        $stations = Station::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('governorate', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $governorates = $this->getGovernorates();

        return view('settings.stations.index', compact('stations', 'governorates'));
    }

    public function create()
    {
        $governorates = $this->getGovernorates();
        return view('settings.stations.create', compact('governorates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'governorate' => 'nullable|string|max:255',
        ]);

        $station = Station::create($validated);

        Log::record('create_station', 'إضافة مخفر: ' . $station->name);

        return redirect()
            ->route('settings.stations.index')
            ->with('success', 'تم إضافة المخفر بنجاح');
    }

    public function edit(Station $station)
    {
        $governorates = $this->getGovernorates();
        return view('settings.stations.edit', compact('station', 'governorates'));
    }

    public function update(Request $request, Station $station)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'governorate' => 'nullable|string|max:255',
        ]);

        $station->update($validated);

        Log::record('update_station', 'تعديل مخفر: ' . $station->name);

        return redirect()
            ->route('settings.stations.index')
            ->with('success', 'تم تحديث المخفر بنجاح');
    }

    public function destroy(Station $station)
    {
        Log::record('delete_station', 'حذف مخفر: ' . $station->name);
        $station->delete();

        return redirect()
            ->route('settings.stations.index')
            ->with('success', 'تم حذف المخفر بنجاح');
    }

    private function getGovernorates(): array
    {
        return [
            'العاصمة', 'حولي', 'الفروانية', 'الجهراء', 'الأحمدي', 'مبارك الكبير'
        ];
    }
}
