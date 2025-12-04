<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Station;
use App\Models\Port;
use App\Models\Log;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        $records = Record::with('creator')
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stations = Station::orderBy('name')->get();
        $ports = Port::orderBy('name')->get();
        $governorates = $this->getGovernorates();
        $ranks = $this->getRanks();

        return view('records.index', compact('records', 'stations', 'ports', 'governorates', 'ranks'));
    }

    public function create()
    {
        $this->authorize('create', Record::class);
        
        $stations = Station::orderBy('name')->get();
        $ports = Port::orderBy('name')->get();
        $governorates = $this->getGovernorates();
        $ranks = $this->getRanks();
        $actionTypes = $this->getActionTypes();

        return view('records.create', compact('stations', 'ports', 'governorates', 'ranks', 'actionTypes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Record::class);

        $validated = $request->validate([
            'record_number' => 'required|string',
            'military_id' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'fourth_name' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'governorate' => 'nullable|string|max:255',
            'station' => 'nullable|string|max:255',
            'action_type' => 'nullable|string|max:255',
            'ports' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'round_date' => 'required|date',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['tracking_number'] = $this->generateTrackingNumber();

        $record = Record::create($validated);

        Log::record('create_record', 'إضافة سجل جديد: ' . $record->record_number . ' - رقم التتبع: ' . $record->tracking_number);

        return redirect()
            ->route('records.index')
            ->with('success', 'تم إضافة السجل بنجاح - رقم التتبع: ' . $record->tracking_number);
    }

    public function show(Record $record)
    {
        $this->authorize('view', $record);
        return view('records.show', compact('record'));
    }

    public function edit(Record $record)
    {
        $this->authorize('update', $record);
        
        $stations = Station::orderBy('name')->get();
        $ports = Port::orderBy('name')->get();
        $governorates = $this->getGovernorates();
        $ranks = $this->getRanks();
        $actionTypes = $this->getActionTypes();

        return view('records.edit', compact('record', 'stations', 'ports', 'governorates', 'ranks', 'actionTypes'));
    }

    public function update(Request $request, Record $record)
    {
        $this->authorize('update', $record);

        $validated = $request->validate([
            'record_number' => 'required|string',
            'military_id' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'fourth_name' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'governorate' => 'nullable|string|max:255',
            'station' => 'nullable|string|max:255',
            'action_type' => 'nullable|string|max:255',
            'ports' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'round_date' => 'required|date',
        ]);

        $record->update($validated);

        Log::record('update_record', 'تعديل سجل: ' . $record->record_number . ' - رقم التتبع: ' . $record->tracking_number);

        return redirect()
            ->route('records.index')
            ->with('success', 'تم تحديث السجل بنجاح');
    }

    public function destroy(Record $record)
    {
        $this->authorize('delete', $record);

        Log::record('delete_record', 'حذف سجل: ' . $record->record_number . ' - رقم التتبع: ' . $record->tracking_number);
        $record->delete();

        return redirect()
            ->route('records.index')
            ->with('success', 'تم حذف السجل بنجاح');
    }

    private function generateTrackingNumber(): string
    {
        $year = date('Y');
        $prefix = 'TRK';
        
        $lastRecord = Record::whereYear('created_at', $year)
            ->whereNotNull('tracking_number')
            ->orderByRaw("CAST(SUBSTRING(tracking_number FROM '[0-9]+$') AS INTEGER) DESC")
            ->first();
        
        if ($lastRecord && preg_match('/(\d+)$/', $lastRecord->tracking_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . '-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
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
