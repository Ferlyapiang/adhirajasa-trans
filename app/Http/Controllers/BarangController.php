<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\ItemType;
use App\Models\Customer;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with('customer')->get();
        return view('master-data.barang.index', compact('barangs'));
    }

    public function create()
    {
        $itemTypes = ItemType::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get(); // Fetch active customers
        return view('master-data.barang.create', compact('itemTypes', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'jenis' => 'required',
            'nomer_rak' => 'required',
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
        $itemTypes = ItemType::where('status', 'active')->get();
        $customers = Customer::where('status', 'active')->get(); // Fetch active customers
        return view('master-data.barang.edit', compact('barang', 'itemTypes', 'customers'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required',
            'jenis' => 'required',
            'nomer_rak' => 'required',
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
