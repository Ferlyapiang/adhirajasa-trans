<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukItem;
use App\Models\BarangKeluar;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\LogData;
use Illuminate\Support\Facades\DB;
use App\Models\JenisMobil;

class BarangMasukController extends Controller
{

    public function index() {
        $user = Auth::user();
    
        $barangMasuks = BarangMasuk::select(
            'barang_masuks.id AS barang_masuk_id',
            'barang_masuks.tanggal_masuk',
            'barang_masuks.joc_number',
            'barangs.nama_barang AS nama_barang',
            'customers.name AS nama_customer',
            'warehouses.name AS nama_gudang',
            'type_mobil.type AS nama_type_mobil',
            'barang_masuks.nomer_polisi',
            'barang_masuks.nomer_container',
            'barang_masuk_items.notes',
            'barang_masuk_items.qty as fifo_in',
            DB::raw('IFNULL(barang_keluar_items.qty, 0) AS fifo_out'),
            'barang_masuk_items.unit',
            DB::raw('(barang_masuk_items.qty - IFNULL(barang_keluar_items.qty, 0)) AS fifo_sisa')
        )
        ->join('barang_masuk_items', 'barang_masuks.id', '=', 'barang_masuk_items.barang_masuk_id')
        ->join('barangs', 'barang_masuk_items.barang_id', '=', 'barangs.id')
        ->join('customers', 'barang_masuks.customer_id', '=', 'customers.id')
        ->join('warehouses', 'barang_masuks.gudang_id', '=', 'warehouses.id')
        ->join('type_mobil', 'barang_masuks.type_mobil_id', '=', 'type_mobil.id')
        ->leftjoin('barang_keluar_items', 'barang_masuk_items.id', '=', 'barang_keluar_items.id');
    
        if ($user->warehouse_id) {
            $barangMasuks = $barangMasuks->where('barang_masuks.gudang_id', $user->warehouse_id);
        }
    
        // Order by tanggal_masuk and get the results
        $barangMasuks = $barangMasuks->orderBy('barang_masuks.tanggal_masuk', 'desc')->get();
    
        return view('data-gudang.barang-masuk.index', compact('barangMasuks'));
    }
    
    public function showDetail($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangs = Barang::all();
        $pemilik = Customer::all();
        $gudangs = Warehouse::all();
        $items = $barangMasuk->items;
        
        $typeMobilOptions = JenisMobil::all();

        return view('data-gudang.barang-masuk.detail', compact('barangMasuk', 'barangs', 'pemilik', 'gudangs', 'items', 'typeMobilOptions'));
    }

    public function create()
    {
        $user = Auth::user();
        $gudangs = Warehouse::all();
        if ($user->warehouse_id) {
            $pemilik = Customer::where('status', 'active')
                                ->where('warehouse_id', $user->warehouse_id)
                                ->get();
        } else {
            $pemilik = Customer::where('status', 'active')->get();
        }
    
        $barangs = Barang::all();
        $typeMobilOptions = JenisMobil::all();
    
        return view('data-gudang.barang-masuk.create', compact('barangs', 'pemilik', 'gudangs', 'user', 'typeMobilOptions'));
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if (is_null($user->warehouse_id)) {
                $request->validate([
                    'gudang_id' => 'required|exists:warehouses,id',
                ]);
            } else {
                $request['gudang_id'] = $user->warehouse_id;
            }
    
            $request->validate([
                'tanggal_masuk' => 'required|date',
                'gudang_id' => 'required|exists:warehouses,id',
                'customer_id' => 'required|exists:customers,id',
                'type_mobil_id' => 'required|exists:type_mobil,id',
                'nomer_polisi' => 'nullable|string',
                'nomer_container' => 'nullable|string',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.qty' => 'required|numeric',
                'items.*.unit' => 'required|string',
                'items.*.notes' => 'required|string',
            ]);

            $datePrefix = now()->format('Ymd');
            $latestJoc = BarangMasuk::where('joc_number', 'like', 'ATS-' . $datePrefix . '%')
                ->orderBy('joc_number', 'desc')
                ->first();

            if ($latestJoc) {
                $lastNumber = (int)substr($latestJoc->joc_number, -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $jocNumber = 'ATS-' . $datePrefix . $newNumber;

            $barangMasuk = BarangMasuk::create([
                'joc_number' => $jocNumber,
                'tanggal_masuk' => $request->tanggal_masuk,
                'gudang_id' => $request->gudang_id,
                'customer_id' => $request->customer_id,
                'type_mobil_id' => $request->type_mobil_id, 
                'nomer_polisi' => $request->nomer_polisi ?? "",
                'nomer_container' => $request->nomer_container ?? "",     
            ]);

            $items = json_decode($request->items, true);
            Log::info('Decoded Items:', ['items' => $items]);

            if (is_array($items)) {
                DB::transaction(function () use ($barangMasuk, $items) {
                    foreach ($items as $item) {
                        Log::info('Saving Item:', ['item' => $item]);
                        BarangMasukItem::create([
                            'barang_id' => $item['id'],
                            'qty' => $item['quantity'],
                            'unit' => $item['unit'],
                            'notes' => $item['notes'],
                            'barang_masuk_id' => $barangMasuk->id,
                        ]);
                    }
                });
            }

            LogData::create([
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
                'action' => 'insert',
                'details' => 'Created barang masuk ID: ' . $barangMasuk->id . ' with data: ' . json_encode($request->all())
            ]);

            // Return redirect with success message
            return redirect()->route('data-gudang.barang-masuk.index')->with('success', 'Barang Masuk berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error and return redirect with error message
            Log::error('Error in storing Barang Masuk:', ['error' => $e->getMessage()]);
            return redirect()->route('data-gudang.barang-masuk.index')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }


    public function edit($id)
    {
        $user = Auth::user();
        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangs = Barang::all();
        if ($user->warehouse_id) {
            $pemilik = Customer::where('status', 'active')
                                ->where('warehouse_id', $user->warehouse_id)
                                ->get();
        } else {
            $pemilik = Customer::where('status', 'active')->get();
        }
        $gudangs = Warehouse::all();
        $items = $barangMasuk->items;
        $typeMobilOptions = JenisMobil::all();

        return view('data-gudang.barang-masuk.edit', compact('barangMasuk', 'barangs', 'pemilik', 'gudangs', 'items', 'typeMobilOptions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tanggal_masuk' => 'required|date',
                'gudang_id' => 'required|exists:warehouses,id',
                'customer_id' => 'required|exists:customers,id',
                'type_mobil_id' => 'required|exists:type_mobil,id', 
                'nomer_polisi' => 'nullable|string',
                'nomer_container' => 'nullable|string',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.qty' => 'required|numeric',
                'items.*.unit' => 'required|string',
                'items.*.notes' => 'nullable|string',
            ]);

            $barangMasuk = BarangMasuk::findOrFail($id);

            $barangMasuk->update([
                'tanggal_masuk' => $request->tanggal_masuk,
                'gudang_id' => $request->gudang_id,
                'customer_id' => $request->customer_id,
                'type_mobil_id' => $request->type_mobil_id,
                'nomer_polisi' => $request->nomer_polisi ?? "", 
                'nomer_container' => $request->nomer_container ?? "", 
            ]);

            $items = json_decode($request->items, true);
            Log::info('Decoded Items:', ['items' => $items]);

            DB::transaction(function () use ($barangMasuk, $items) {
                BarangMasukItem::where('barang_masuk_id', $barangMasuk->id)->delete();

                foreach ($items as $item) {
                    Log::info('Saving Item:', ['item' => $item]);
                    BarangMasukItem::create([
                        'barang_id' => $item['nama_barang'],
                        'qty' => $item['quantity'],
                        'unit' => $item['unit'],
                        'notes' => $item['notes'],
                        'barang_masuk_id' => $barangMasuk->id,
                    ]);
                }
            });

            LogData::create([
                'user_id' => Auth::id(),
                'name' => Auth::user()->name,
                'action' => 'update',
                'details' => 'Updated barang masuk ID: ' . $barangMasuk->id . ' with data: ' . json_encode($request->all())
            ]);

            return redirect()->route('data-gudang.barang-masuk.index')->with('success', 'Barang Masuk berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error in updating Barang Masuk:', ['error' => $e->getMessage()]);
            return redirect()->route('data-gudang.barang-masuk.index')->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
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
            $barangs = Barang::where('pemilik', $pemilik) // Assuming `pemilik` is the foreign key column
                ->select('id', 'nama_barang', 'jenis') // Select only necessary fields
                ->get();

            // Return items as JSON
            return response()->json($barangs);
        } catch (\Exception $e) {
            // Return error message for debugging
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
