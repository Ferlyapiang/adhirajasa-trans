<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogData;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return view('master-data.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('master-data.warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $warehouse = Warehouse::create($request->all());

        // Log the create action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created warehouse ID: ' . $warehouse->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('master-data.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $warehouse->update($request->all());

        // Log the update action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'update',
            'details' => 'Updated warehouse ID: ' . $warehouse->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Log the delete action before deleting
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'delete',
            'details' => 'Deleted warehouse ID: ' . $warehouse->id . ' with data: ' . json_encode($warehouse->only('name', 'address', 'status'))
        ]);

        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
