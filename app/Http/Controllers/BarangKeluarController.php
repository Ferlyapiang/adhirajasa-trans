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

use Illuminate\Support\Facades\Log;



class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluars = BarangKeluar::with(['gudang', 'customer', 'bankTransfer', 'items.barang'])
            ->orderBy('tanggal_keluar', 'desc') // Mengurutkan berdasarkan tanggal_keluar secara menurun
            ->get();

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
        // Decode JSON string to array if items are sent as a JSON string
        $request->merge(['items' => json_decode($request->input('items'), true)]);
        dd($request->all());
        // Validate the request data
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
            'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
        ]);

        // Prepare Barang Keluar data
        $barangKeluarData = [
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'gudang_id' => $validated['gudang_id'],
            'customer_id' => $validated['customer_id'],
            'nomer_container' => $validated['nomer_container'],
            'nomer_polisi' => $validated['nomer_polisi'],
            'bank_transfer_id' => $validated['bank_transfer_id'],
        ];

        // Extract items data
        $items = $validated['items'];

        try {
            // Database transaction
            DB::transaction(function () use ($barangKeluarData, $items) {
                // Create Barang Keluar record
                $barangKeluar = BarangKeluar::create($barangKeluarData);

                // Iterate over items and create BarangKeluarItem
                foreach ($items as $item) {
                    Log::info('Processing Item:', [
                        'barang_id' => (int) $item['barang_id'],
                        'barang_masuk_id' => (int) $item['barang_masuk_id'],
                    ]);

                    BarangKeluarItem::create([
                        'barang_id' => (int) $item['barang_id'],
                        'no_ref' => $item['no_ref'],
                        'qty' => $item['qty'],
                        'unit' => $item['unit'],
                        'harga' => $item['harga'],
                        'total_harga' => $item['total_harga'],
                        'barang_masuk_id' => (int) $item['barang_masuk_id'],
                        'barang_keluar_id' => $barangKeluar->id,
                    ]);
                }

                // Log the operation
                LogData::create([
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'name' => Auth::check() ? Auth::user()->name : 'unknown',
                    'action' => 'insert',
                    'details' => 'Created Barang Keluar ID: ' . $barangKeluar->id . ' with data: ' . json_encode($barangKeluarData)
                ]);
            });

            // Redirect with success message
            return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar created successfully.');
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Exception caught:', [
                'user_id' => Auth::check() ? Auth::id() : 'unknown',
                'user_name' => Auth::check() ? Auth::user()->name : 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Optional: add stack trace for debugging
            ]);

            // Redirect with error message
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('items.barang');
        return view('data-gudang.barang-keluar.show', compact('barangKeluar'));
    }

    public function edit($id)
    {
        $barangKeluar = BarangKeluar::with('items')->findOrFail($id); // Fetch Barang Keluar data along with its items
        $warehouses = Warehouse::all();
        $customers = Customer::all();
        $bankTransfers = BankData::all();
        $barangs = Barang::all(); // Fetch all Barang data

        return view('data-gudang.barang-keluar.edit', compact('barangKeluar', 'warehouses', 'customers', 'bankTransfers', 'barangs'));
    }


    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     // Decode JSON string to array if items are sent as a JSON string
    //     $request->merge(['items' => json_decode($request->input('items'), true)]);

    //     // Validate the request data
    //     $validated = $request->validate([
    //         'tanggal_keluar' => 'required|date',
    //         'gudang_id' => 'required|exists:warehouses,id',
    //         'customer_id' => 'required|exists:customers,id',
    //         'nomer_container' => 'nullable|string|max:191',
    //         'nomer_polisi' => 'nullable|string|max:191',
    //         'bank_transfer_id' => 'nullable|exists:bank_datas,id',
    //         'items' => 'required|array',
    //         'items.*.barang_id' => 'required|exists:barangs,id',
    //         'items.*.no_ref' => 'nullable|string|max:191',
    //         'items.*.qty' => 'required|integer|min:1',
    //         'items.*.unit' => 'required|string|max:50',
    //         'items.*.harga' => 'nullable|numeric|min:0',
    //         'items.*.total_harga' => 'nullable|numeric|min:0',
    //         'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
    //     ]);

    //     // Prepare Barang Keluar data
    //     $barangKeluarData = [
    //         'tanggal_keluar' => $validated['tanggal_keluar'],
    //         'gudang_id' => $validated['gudang_id'],
    //         'customer_id' => $validated['customer_id'],
    //         'nomer_container' => $validated['nomer_container'],
    //         'nomer_polisi' => $validated['nomer_polisi'],
    //         'bank_transfer_id' => $validated['bank_transfer_id'],
    //     ];

    //     // Extract items data
    //     $items = $validated['items'];

    //     try {
    //         // Database transaction
    //         DB::transaction(function () use ($id, $barangKeluarData, $items) {
    //             // Update Barang Keluar record
    //             $barangKeluar = BarangKeluar::findOrFail($id);
    //             $barangKeluar->update($barangKeluarData);

    //             // Delete existing items related to this Barang Keluar
    //             BarangKeluarItem::where('barang_keluar_id', $id)->delete();

    //             // Iterate over items and create BarangKeluarItem
    //             foreach ($items as $item) {
    //                 BarangKeluarItem::create([
    //                     'barang_id' => (int) $item['barang_id'],
    //                     'no_ref' => $item['no_ref'],
    //                     'qty' => $item['qty'],
    //                     'unit' => $item['unit'],
    //                     'harga' => $item['harga'],
    //                     'total_harga' => $item['total_harga'],
    //                     'barang_masuk_id' => (int) $item['barang_masuk_id'],
    //                     'barang_keluar_id' => $barangKeluar->id,
    //                 ]);
    //             }

    //             // Log the operation
    //             LogData::create([
    //                 'user_id' => Auth::check() ? Auth::id() : null,
    //                 'name' => Auth::check() ? Auth::user()->name : 'unknown',
    //                 'action' => 'update',
    //                 'details' => 'Updated Barang Keluar ID: ' . $barangKeluar->id . ' with data: ' . json_encode($barangKeluarData)
    //             ]);
    //         });

    //         // Redirect with success message
    //         return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar updated successfully.');
    //     } catch (\Exception $e) {
    //         // Log the exception
    //         Log::error('Exception caught:', [
    //             'user_id' => Auth::check() ? Auth::id() : 'unknown',
    //             'user_name' => Auth::check() ? Auth::user()->name : 'unknown',
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString() // Optional: add stack trace for debugging
    //         ]);

    //         // Redirect with error message
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }

    public function update(Request $request, $id)
{
    // Decode JSON string to array if items are sent as a JSON string
    $request->merge(['items' => json_decode($request->input('items'), true)]);
    
    // Dump and die to inspect the request data after merging
    // dd($request->all());

    // Validate the request data
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
        'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
    ]);

    // Dump and die to inspect the validated data
    // dd($validated);

    // Prepare Barang Keluar data
    $barangKeluarData = [
        'tanggal_keluar' => $validated['tanggal_keluar'],
        'gudang_id' => $validated['gudang_id'],
        'customer_id' => $validated['customer_id'],
        'nomer_container' => $validated['nomer_container'],
        'nomer_polisi' => $validated['nomer_polisi'],
        'bank_transfer_id' => $validated['bank_transfer_id'],
    ];

    // Extract items data
    $items = $validated['items'];

    // Dump and die to inspect Barang Keluar data and items before transaction
    // dd($barangKeluarData, $items);

    try {
        // Database transaction
        DB::transaction(function () use ($id, $barangKeluarData, $items) {
            // Update Barang Keluar record
            $barangKeluar = BarangKeluar::findOrFail($id);
            $barangKeluar->update($barangKeluarData);

            // Delete existing items related to this Barang Keluar
            BarangKeluarItem::where('barang_keluar_id', $id)->delete();

            // Iterate over items and create BarangKeluarItem
            foreach ($items as $item) {
                BarangKeluarItem::create([
                    'barang_id' => (int) $item['barang_id'],
                    'no_ref' => $item['no_ref'],
                    'qty' => $item['qty'],
                    'unit' => $item['unit'],
                    'harga' => $item['harga'],
                    'total_harga' => $item['total_harga'],
                    'barang_masuk_id' => (int) $item['barang_masuk_id'],
                    'barang_keluar_id' => $barangKeluar->id,
                ]);
            }

            // Log the operation
            LogData::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'name' => Auth::check() ? Auth::user()->name : 'unknown',
                'action' => 'update',
                'details' => 'Updated Barang Keluar ID: ' . $barangKeluar->id . ' with data: ' . json_encode($barangKeluarData)
            ]);
        });

        // Redirect with success message
        return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar updated successfully.');
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Exception caught:', [
            'user_id' => Auth::check() ? Auth::id() : 'unknown',
            'user_name' => Auth::check() ? Auth::user()->name : 'unknown',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString() // Optional: add stack trace for debugging
        ]);

        // Redirect with error message
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
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

        $barangMasuk = BarangMasuk::where('customer_id', $customerId)
            ->where('gudang_id', $warehouseId)
            ->with('items.barang')
            ->orderBy('joc_number', 'asc')
            ->get();


        $barangKeluarSummary = BarangKeluarItem::select('barang_id', 'no_ref', DB::raw('SUM(qty) as total_qty_keluar'))
            ->join('barang_keluars', 'barang_keluar_items.barang_keluar_id', '=', 'barang_keluars.id')
            ->whereIn('barang_keluars.customer_id', [$customerId])
            ->whereIn('barang_keluars.gudang_id', [$warehouseId])
            ->groupBy('barang_id', 'no_ref')
            ->get()
            ->keyBy(function ($item) {
                return $item->barang_id . '-' . $item->no_ref;
            });

        $items = $barangMasuk->flatMap(function ($barangMasuk) use ($barangKeluarSummary) {
            return $barangMasuk->items->map(function ($item) use ($barangMasuk, $barangKeluarSummary) {
                $key = $item->barang_id . '-' . $barangMasuk->joc_number;
                $totalQtyKeluar = $barangKeluarSummary->get($key, (object) ['total_qty_keluar' => 0])->total_qty_keluar;
                $qtyMasuk = $item->qty;


                if ($qtyMasuk > $totalQtyKeluar || $qtyMasuk - $totalQtyKeluar < 0) {
                    return [
                        'id' => $item->id,
                        'barang_masuk_id' => $item->barang_masuk_id,
                        'barang_id' => $item->barang_id,
                        'barang_name' => $item->barang->nama_barang,
                        'qty' => $item->qty,
                        'unit' => $item->unit,
                        'joc_number' => $barangMasuk->joc_number,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                }

                return null;
            })->filter();
        });

        return response()->json(['items' => $items]);
    }



    public function getCustomersByWarehouse($warehouseId)
    {
        $customers = BarangMasuk::where('gudang_id', $warehouseId)
            ->distinct('customer_id')
            ->pluck('customer_id')
            ->map(function ($customerId) {
                return Customer::find($customerId);
            });

        return response()->json(['customers' => $customers]);
    }
}
