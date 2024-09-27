<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\ItemType;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    if ($user->warehouse_id) {
        $barangs = Barang::with('customer')
                    ->whereHas('customer', function ($query) use ($user) {
                        $query->where('warehouse_id', $user->warehouse_id);
                    })
                    ->get();
    } else {
        $barangs = Barang::with('customer')->get();
    }
    
    return view('master-data.barang.index', compact('barangs'));
}

    public function create()
{
    $itemTypes = ItemType::where('status', 'active')->get();

    // Fetch only active customers related to the user's warehouse_id
    $user = Auth::user();
    
    if ($user->warehouse_id) {
        // Fetch only active customers whose warehouse_id matches the user's
        $customers = Customer::where('status', 'active')
                            ->where('warehouse_id', $user->warehouse_id)
                            ->get();
    } else {
        // If no warehouse_id, fetch all active customers
        $customers = Customer::where('status', 'active')->get();
    }

    return view('master-data.barang.create', compact('itemTypes', 'customers'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'jenis' => 'required',
            'sku' => 'required',
            'pemilik' => 'required',
            'status' => 'required',
        ]);

        Barang::create($request->all());

        return redirect()->route('master-data.barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view('master-data.barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
{
    $user = Auth::user();

    if ($user->warehouse_id) {
        $customers = Customer::where('status', 'active')
                             ->where('warehouse_id', $user->warehouse_id)
                             ->get();
    } else {
        $customers = Customer::where('status', 'active')->get();
    }

    $itemTypes = ItemType::where('status', 'active')->get();
    return view('master-data.barang.edit', compact('barang', 'itemTypes', 'customers'));
}

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required',
            'jenis' => 'required',
            'sku' => 'required',
            'pemilik' => 'required',
            'status' => 'required',
        ]);

        $barang->update($request->all());

        return redirect()->route('master-data.barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()->route('master-data.barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
