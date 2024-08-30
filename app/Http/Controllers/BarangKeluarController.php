<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarItem;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\BankData;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluars = BarangKeluar::with(['gudang', 'owner', 'bankTransfer'])->get();
        return view('data-gudang.barang-keluar.index', compact('barangKeluars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $customers = Customer::all();
        $bankTransfers = BankData::all();
        $barangs = Barang::all(); // Fetch all Barang data
        return view('data-gudang.barang-keluar.create', compact('warehouses', 'customers', 'bankTransfers', 'barangs'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            $validated = $request->validate([
                'tanggal_keluar' => 'required|date',
                'gudang_id' => 'required|exists:warehouses,id',
                'owner_id' => 'required|exists:customers,id',
                'nomer_container' => 'required|string|max:191',
                'nomer_polisi' => 'nullable|string|max:191',
                'bank_transfer_id' => 'nullable|exists:bank_data,id',
                'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.qty' => 'required|integer',
                'items.*.unit' => 'required|string',
                'items.*.harga' => 'required|numeric',
            ]);

            $barangKeluar = BarangKeluar::create($validated);

            // Handle items
            foreach ($request->input('items', []) as $item) {
                $barangKeluar->items()->create([
                    'barang_masuk_id' => $item['barang_masuk_id'],
                    'barang_id' => $item['barang_id'],
                    'qty' => $item['qty'],
                    'unit' => $item['unit'],
                    'harga' => $item['harga'],
                ]);
            }

            return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar created successfully.');
        }


    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('items');
        return view('data-gudang.barang-keluar.show', compact('barangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barangKeluar)
    {
        $warehouses = Warehouse::all();
        $customers = Customer::all();
        $bankTransfers = BankData::all();
        $barangKeluar->load('items');
        return view('data-gudang.barang-keluar.edit', compact('barangKeluar', 'warehouses', 'customers', 'bankTransfers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'gudang_id' => 'required|exists:warehouses,id',
            'customer_id' => 'required|exists:customers,id',
            'nomer_container' => 'required|string|max:191',
            'nomer_polisi' => 'nullable|string|max:191',
            'bank_transfer_id' => 'nullable|exists:bank_data,id',
            'items' => 'array',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $barangKeluar->update($validated);

        // Handle items
        $barangKeluar->items()->delete();
        foreach ($request->input('items', []) as $item) {
            $barangKeluar->items()->create([
                'barang_id' => $item['barang_id'],
                'qty' => $item['qty'],
                'unit' => $item['unit'],
                'harga' => $item['harga'],
            ]);
        }

        return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar)
    {
        $barangKeluar->items()->delete();
        $barangKeluar->delete();
        return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar deleted successfully.');
    }
}
