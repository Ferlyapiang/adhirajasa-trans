<?php

namespace App\Http\Controllers;

use App\Models\BankData;
use App\Models\LogData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankDataController extends Controller
{
    public function index()
    {
        $bankData = BankData::all();
        return view('master-data.bank-data.index', compact('bankData'));
    }

    public function create()
    {
        return view('master-data.bank-data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'warehouse_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $bankData = BankData::create($request->all());

        // Log the create action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created bank data ID: ' . $bankData->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('master-data.bank-data.index')->with('success', 'Bank Data added successfully.');
    }

    public function edit(BankData $bankData)
    {
        return view('master-data.bank-data.edit', compact('bankData'));
    }

    public function update(Request $request, BankData $bankData)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'warehouse_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $bankData->update($request->all());

        // Log the update action
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'update',
            'details' => 'Updated bank data ID: ' . $bankData->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('master-data.bank-data.index')->with('success', 'Bank Data updated successfully.');
    }

    public function destroy(BankData $bankData)
    {
        // Log the delete action before deleting
        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'delete',
            'details' => 'Deleted bank data ID: ' . $bankData->id . ' with data: ' . json_encode($bankData->only('bank_name', 'account_number', 'account_name', 'warehouse_name', 'status'))
        ]);

        $bankData->delete();

        return redirect()->route('master-data.bank-data.index')->with('success', 'Bank Data deleted successfully.');
    }
}
