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

    public function index()
{
    $user = Auth::user();

    if ($user->warehouse_id) {
        $barangMasuks = BarangMasuk::with(['items', 'customer'])
            ->where('gudang_id', $user->warehouse_id)
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
    } else {
        $barangMasuks = BarangMasuk::with(['items', 'customer'])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();
    }

    $barangKeluars = BarangKeluar::with('items')->get();
    
    $typeMobilOptions = JenisMobil::all();
    $fifoData = [];

    foreach ($barangMasuks as $barangMasuk) {
        $fifo_in = $barangMasuk->items->sum('qty');
        $fifo_out = 0;

        foreach ($barangKeluars as $barangKeluar) {
            foreach ($barangKeluar->items as $item) {
                // Calculate FIFO out based on barang_masuk_id matching
                if ($item->barang_masuk_id === $barangMasuk->id) {
                    $fifo_out += $item->qty;
                }
            }
        }

        $fifoData[$barangMasuk->id] = [
            'fifo_in' => $fifo_in,
            'fifo_out' => $fifo_out,
            'fifo_sisa' => $fifo_in - $fifo_out,
        ];
    }

    foreach ($barangMasuks as $barangMasuk) {
        if (isset($fifoData[$barangMasuk->id])) {
            $barangMasuk->fifo_in = $fifoData[$barangMasuk->id]['fifo_in'];
            $barangMasuk->fifo_out = $fifoData[$barangMasuk->id]['fifo_out'];
            $barangMasuk->fifo_sisa = $fifoData[$barangMasuk->id]['fifo_sisa'];
        }
    }

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
