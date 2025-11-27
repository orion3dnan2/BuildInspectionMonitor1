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
        $this->authorize('create', Record::class);
        
        $records = Record::with('creator')
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('records.index', compact('records'));
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
            'record_number' => 'required|string|unique:records',
            'military_id' => 'required|string',
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

        $record = Record::create($validated);

        Log::record('create_record', 'إضافة سجل جديد: ' . $record->record_number);

        return redirect()
            ->route('records.index')
            ->with('success', 'تم إضافة السجل بنجاح');
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
            'record_number' => 'required|string|unique:records,record_number,' . $record->id,
            'military_id' => 'required|string',
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

        Log::record('update_record', 'تعديل سجل: ' . $record->record_number);

        return redirect()
            ->route('records.index')
            ->with('success', 'تم تحديث السجل بنجاح');
    }

    public function destroy(Record $record)
    {
        $this->authorize('delete', $record);

        Log::record('delete_record', 'حذف سجل: ' . $record->record_number);
        $record->delete();

        return redirect()
            ->route('records.index')
            ->with('success', 'تم حذف السجل بنجاح');
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

    private function getActionTypes(): array
    {
        return [
            'تفتيش', 'رقابة', 'متابعة', 'زيارة ميدانية', 'تحقيق', 'أخرى'
        ];
    }
}
