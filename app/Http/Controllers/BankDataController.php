<?php

namespace App\Http\Controllers;

use App\Models\BankData;
use App\Models\LogData;
use App\Models\Warehouse; // Import Warehouse model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankDataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            if ($user->warehouse_id) {
                
                $bankDatas = BankData::with('warehouse')
                    ->where('warehouse_id', $user->warehouse_id)
                    ->get();
            } else {
                
                $bankDatas = BankData::with('warehouse')->get();
            }
        }

        
        return view('master-data.bank-data.index', compact('bankDatas'));
    }


    public function create()
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('alert', 'You must be logged in to access this page.');
    }

    $warehouses = Warehouse::where('status', 'active')->get();
    $existingBankData = BankData::where('warehouse_id', $user->warehouse_id)->pluck('status_bank')->toArray();
    return view('master-data.bank-data.create', compact('warehouses', 'user', 'existingBankData'));
}


public function store(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'account_name' => 'required|string|max:255',
        'warehouse_id' => 'required|exists:warehouses,id',
        'status_bank' => 'required|string|max:50',
        'status' => 'required|in:active,inactive',
    ]);
    
    if ($user->warehouse_id) {
    if ($user->warehouse_id != $request->warehouse_id) {
        return redirect()->back()->withErrors(['warehouse_id' => 'Anda hanya dapat menggunakan gudang yang telah ditentukan untuk Anda.']);
    }
}

$existingBankData = BankData::where('warehouse_id', $request->warehouse_id)->pluck('status_bank')->toArray();

if (in_array('PT', $existingBankData) && in_array('Pribadi', $existingBankData)) {
    return redirect()->back()->withErrors([
        'status_bank' => 'Gudang ini sudah memiliki data bank dengan status PT dan Pribadi. ' .
                         'Data Keseluruhan: ' .
                         'Nama Bank: ' . $request->bank_name . ', ' .
                         'Nomor Rekening: ' . $request->account_number . ', ' .
                         'Nama Rekening: ' . $request->account_name . '.'
    ])->withInput();
}

if (in_array($request->status_bank, $existingBankData)) {
    return redirect()->back()->withErrors([
        'status_bank' => 'Data Keseluruhan: ' .
                         'Nama Bank: ' . $request->bank_name . ', ' .
                         'Nomor Rekening: ' . $request->account_number . ', ' .
                         'Nama Rekening: ' . $request->account_name . '. ' .
                         'Gudang ini sudah memiliki data bank dengan status: ' . $request->status_bank
    ])->withInput();
}


    $bankData = BankData::create([
        'bank_name' => $request->bank_name,
        'account_number' => $request->account_number,
        'account_name' => $request->account_name,
        'warehouse_id' => $request->warehouse_id,
        'status_bank' => $request->status_bank,
        'status' => $request->status,
    ]);

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
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login')->with('alert', 'You must be logged in to access this page.');
    }
    
    $warehouses = $user->warehouse_id ? Warehouse::where('id', $user->warehouse_id)->get() : Warehouse::all();
    
    return view('master-data.bank-data.edit', compact('bankData', 'warehouses', 'user'));
}

    public function update(Request $request, BankData $bankData)
{
    $request->validate([
        'bank_name' => 'required|string|max:255',
        'account_number' => 'required|string|max:255',
        'account_name' => 'required|string|max:255',
        'warehouse_id' => 'required|exists:warehouses,id',
        'status_bank' => 'required|string|max:50',
        'status' => 'required|in:active,inactive',
    ]);
    
    $bankData->update([
        'bank_name' => $request->bank_name,
        'account_number' => $request->account_number,
        'account_name' => $request->account_name,
        'warehouse_id' => $request->warehouse_id, 
        'status_bank' => $request->status_bank,
        'status' => $request->status,
    ]);

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
            'details' => 'Deleted bank data ID: ' . $bankData->id . ' with data: ' . json_encode($bankData->only('bank_name', 'account_number', 'account_name', 'warehouse_id', 'status'))
        ]);

        $bankData->delete();

        return redirect()->route('master-data.bank-data.index')->with('success', 'Bank Data deleted successfully.');
    }
}
