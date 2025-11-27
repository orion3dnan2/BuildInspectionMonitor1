<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Log;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $ports = Port::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('settings.ports.index', compact('ports'));
    }

    public function create()
    {
        return view('settings.ports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ports',
        ]);

        $port = Port::create($validated);

        Log::record('create_port', 'إضافة منفذ: ' . $port->name);

        return redirect()
            ->route('settings.ports.index')
            ->with('success', 'تم إضافة المنفذ بنجاح');
    }

    public function edit(Port $port)
    {
        return view('settings.ports.edit', compact('port'));
    }

    public function update(Request $request, Port $port)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ports,name,' . $port->id,
        ]);

        $port->update($validated);

        Log::record('update_port', 'تعديل منفذ: ' . $port->name);

        return redirect()
            ->route('settings.ports.index')
            ->with('success', 'تم تحديث المنفذ بنجاح');
    }

    public function destroy(Port $port)
    {
        Log::record('delete_port', 'حذف منفذ: ' . $port->name);
        $port->delete();

        return redirect()
            ->route('settings.ports.index')
            ->with('success', 'تم حذف المنفذ بنجاح');
    }
}
