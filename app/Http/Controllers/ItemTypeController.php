<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use App\Models\LogData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemTypeController extends Controller
{
    public function index()
    {
        $itemTypes = ItemType::all();
        return view('master-data.item-types.index', compact('itemTypes'));
    }

    public function create()
    {
        return view('master-data.item-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $itemType = ItemType::create($request->all());

        // Log the create action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created item type ID: ' . $itemType->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('master-data.item-types.index')->with('success', 'Item Type created successfully.');
    }

    public function edit(ItemType $itemType)
    {
        return view('master-data.item-types.edit', compact('itemType'));
    }

    public function update(Request $request, ItemType $itemType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $itemType->update($request->all());

        // Log the update action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'update',
            'details' => 'Updated item type ID: ' . $itemType->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('master-data.item-types.index')->with('success', 'Item Type updated successfully.');
    }

    public function destroy(ItemType $itemType)
    {
        // Log the delete action before deleting
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'delete',
            'details' => 'Deleted item type ID: ' . $itemType->id . ' with data: ' . json_encode($itemType->only('name', 'status'))
        ]);

        $itemType->delete();

        return redirect()->route('master-data.item-types.index')->with('success', 'Item Type deleted successfully.');
    }
}
