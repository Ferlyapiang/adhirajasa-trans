<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarItem;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\BankData;
use App\Models\Barang;
use App\Models\BarangMasuk;
use Illuminate\Support\Facades\Auth;
use App\Models\LogData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluars = BarangKeluar::with(['gudang', 'customer', 'bankTransfer'])->get();
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
            'customer_id' => 'required|exists:customers,id',
            'nomer_container' => 'nullable|string|max:191',
            'nomer_polisi' => 'nullable|string|max:191',
            'bank_transfer_id' => 'nullable|exists:bank_datas,id',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.no_ref' => 'nullable|string|max:191',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.harga' => 'nullable|numeric|min:0',
            'items.*.total_harga' => 'nullable|numeric|min:0',
        ]);

        $barangKeluarData = [
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'gudang_id' => $validated['gudang_id'],
            'customer_id' => $validated['customer_id'],
            'nomer_container' => $validated['nomer_container'],
            'nomer_polisi' => $validated['nomer_polisi'],
            'bank_transfer_id' => $validated['bank_transfer_id'],
        ];

        $items = $validated['items'];

        $barangKeluar = BarangKeluar::create($barangKeluarData);

        DB::transaction(function () use ($barangKeluar, $items) {
            foreach ($items as $item) {
                BarangKeluarItem::create([
                    'barang_id' => $item['barang_id'],
                    'no_ref' => $item['no_ref'],
                    'qty' => $item['qty'],
                    'unit' => $item['unit'],
                    'harga' => $item['harga'],
                    'total_harga' => $item['total_harga'],
                    'barang_keluar_id' => $barangKeluar->id,
                ]);
            }
        });

        LogData::create([
            'user_id' => Auth::id(),
            'name' => Auth::user()->name,
            'action' => 'insert',
            'details' => 'Created barang Keluar ID: ' . $barangKeluar->id . ' with data: ' . json_encode($request->all())
        ]);

        return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('items.barang');
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
        $barangs = Barang::all(); // Fetch all Barang data
        $barangKeluar->load('items');
        return view('data-gudang.barang-keluar.edit', compact('barangKeluar', 'warehouses', 'customers', 'bankTransfers', 'barangs'));
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
            'nomer_container' => 'nullable|string|max:191',
            'nomer_polisi' => 'nullable|string|max:191',
            'bank_transfer_id' => 'nullable|exists:bank_datas,id',
            'items' => 'array',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.no_ref' => 'nullable|string|max:191',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.harga' => 'nullable|numeric|min:0',
            'items.*.total_harga' => 'nullable|numeric|min:0',
        ]);

        // Update BarangKeluar
        $barangKeluar->update($validated);

        // Handle items
        $barangKeluar->items()->delete(); // Delete old items
        foreach ($request->input('items', []) as $item) {
            $barangKeluar->items()->create([
                'barang_id' => $item['barang_id'],
                'no_ref' => $item['no_ref'],
                'qty' => $item['qty'],
                'unit' => $item['unit'],
                'harga' => $item['harga'],
                'total_harga' => $item['total_harga'],
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
    public function getItemsByCustomer($customerId, $warehouseId)
    {
        // Mengambil semua BarangMasuk yang sesuai dengan customer_id dan gudang_id
        $barangMasuk = BarangMasuk::where('customer_id', $customerId)
            ->where('gudang_id', $warehouseId) // Menambahkan filter untuk gudang_id
            ->with('items.barang') // Memuat relasi BarangMasukItem dan Barang
            ->orderBy('joc_number', 'asc') // Mengurutkan berdasarkan joc_number yang paling lama
            ->get();
    
        // Mengambil data BarangMasuk beserta item-itemnya
        $items = $barangMasuk->flatMap(function ($barangMasuk) {
            return $barangMasuk->items->map(function ($item) use ($barangMasuk) {
                // Menambahkan informasi barang_masuk_id dan nama barang ke BarangMasukItem
                return [
                    'id' => $item->id,
                    'barang_masuk_id' => $item->barang_masuk_id,
                    'barang_id' => $item->barang_id,
                    'barang_name' => $item->barang->nama_barang, // Mengambil nama barang dari model Barang
                    'qty' => $item->qty,
                    'unit' => $item->unit,
                    'joc_number' => $barangMasuk->joc_number, // Menambahkan joc_number
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });
        });
    
        return response()->json(['items' => $items]);
    }
    


    public function getCustomersByWarehouse($warehouseId)
    {
        // Mengambil pelanggan yang terkait dengan gudang_id yang diberikan
        $customers = BarangMasuk::where('gudang_id', $warehouseId)
            ->distinct('customer_id') // Menghindari duplikasi pelanggan
            ->pluck('customer_id')    // Mengambil hanya customer_id
            ->map(function ($customerId) {
                return Customer::find($customerId);
            });

        return response()->json(['customers' => $customers]);
    }
}
