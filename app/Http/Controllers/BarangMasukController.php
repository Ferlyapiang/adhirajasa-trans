<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangMasuks = BarangMasuk::with('items')->get();
        return view('data-gudang.barang-masuk.index', compact('barangMasuks'));
    }

    public function create()
    {
        $barangs = Barang::all();
        $pemilik = Customer::all();
        $gudangs = Warehouse::all();

        return view('data-gudang.barang-masuk.create', compact('barangs', 'pemilik', 'gudangs'));
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'gudang_id' => 'required|exists:warehouses,id',
            'customer_id' => 'required|exists:customers,id',
            'nomer_container' => 'required|string',
            'fifo_in' => 'required|numeric',
            'fifo_out' => 'required|numeric',
            'fifo_sisa' => 'required|numeric',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|numeric',
            'items.*.unit' => 'required|string',
        ]);

        // Generate JOC Number
        $datePrefix = now()->format('Ymd');
        $latestJoc = BarangMasuk::where('joc_number', 'like', 'JOC-' . $datePrefix . '%')
            ->orderBy('joc_number', 'desc')
            ->first();

        if ($latestJoc) {
            $lastNumber = (int)substr($latestJoc->joc_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $jocNumber = 'JOC-' . $datePrefix . $newNumber;

        // Create BarangMasuk record
        $barangMasuk = BarangMasuk::create([
            'joc_number' => $jocNumber,
            'tanggal_masuk' => $request->tanggal_masuk,
            'gudang_id' => $request->gudang_id,
            'customer_id' => $request->customer_id,
            'jenis_mobil' => $request->jenis_mobil ?? null,  // Handle nullable fields
            'nomer_polisi' => $request->nomer_polisi ?? null,  // Handle nullable fields
            'nomer_container' => $request->nomer_container,
            'fifo_in' => $request->fifo_in,
            'fifo_out' => $request->fifo_out,
            'fifo_sisa' => $request->fifo_sisa,
        ]);

        // Store associated items
        foreach ($request->items as $item) {
            $barangMasuk->items()->create([
                'barang_id' => $item['barang_id'],
                'qty' => $item['qty'],
                'unit' => $item['unit'],
            ]);
        }

        return redirect()->route('data-gudang.barang-masuk.index')->with('success', 'Barang Masuk berhasil disimpan.');
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $barangs = Barang::all();
        $pemilik = Customer::all();
        $gudangs = Warehouse::all();
        return view('data-gudang.barang-masuk.edit', compact('barangMasuk', 'barangs', 'pemilik', 'gudangs'));
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'gudang_id' => 'required|exists:warehouses,id',
            'customer_id' => 'required|exists:customers,id',
            'nomer_container' => 'nullable|string',
            'fifo_in' => 'required|numeric',
            'fifo_out' => 'required|numeric',
            'fifo_sisa' => 'required|numeric',
        ]);

        $barangMasuk->update([
            'tanggal_masuk' => $request->tanggal_masuk,
            'gudang_id' => $request->gudang_id,
            'customer_id' => $request->customer_id,
            'jenis_mobil' => $request->jenis_mobil ?? null,
            'nomer_polisi' => $request->nomer_polisi ?? null,
            'nomer_container' => $request->nomer_container,
            'fifo_in' => $request->fifo_in,
            'fifo_out' => $request->fifo_out,
            'fifo_sisa' => $request->fifo_sisa,
        ]);

        return redirect()->route('data-gudang.barang-masuk.index')->with('success', 'Data barang masuk berhasil diperbarui.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $barangMasuk->delete();
        return redirect()->route('data-gudang.barang-masuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }

    public function itemsByOwner(Request $request)
    {
        try {
            $pemilik = $request->input('pemilik');

            if (!$pemilik) {
                return response()->json(['error' => 'Pemilik is required'], 400);
            }

            // Fetch items based on pemilik
            $barangs = Barang::where('pemilik', $pemilik)->get();

            // Return items as JSON
            return response()->json($barangs);
        } catch (\Exception $e) {
            // Return error message for debugging
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
