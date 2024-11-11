<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukItem;
use App\Models\Customer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\LogData;
use Illuminate\Support\Facades\DB;
use App\Models\JenisMobil;
use Carbon\Carbon;

class BarangMasukController extends Controller
{

    public function index() {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            $barangMasuks = DB::table('barang_masuks as bm')
                ->select(
                    'bm.id AS barang_masuk_id',
                    DB::raw('MAX(bki.id) AS barang_keluar_id'),  // Menggunakan MAX untuk mendapatkan satu id dari barang keluar
                    'bm.tanggal_masuk',
                    'bm.joc_number',
                    'b.nama_barang AS nama_barang',
                    'c.name AS nama_customer',
                    'w.name AS nama_gudang',
                    'tm.type AS nama_type_mobil',
                    'bm.nomer_polisi',
                    'bm.nomer_container',
                    'bmi.notes',
                    DB::raw('MIN(bmi.qty) AS fifo_in'),  // Mengambil nilai qty pertama dari barang masuk
                    DB::raw('COALESCE(SUM(bki.qty), 0) AS fifo_out'),  // Menghitung total fifo_out, default ke 0 jika tidak ada
                    'bmi.unit',
                    DB::raw('(MIN(bmi.qty) - COALESCE(SUM(bki.qty), 0)) AS fifo_sisa')  // Menghitung fifo_sisa
                )
                ->join('barang_masuk_items as bmi', 'bm.id', '=', 'bmi.barang_masuk_id')
                ->join('barangs as b', 'bmi.barang_id', '=', 'b.id')
                ->join('customers as c', 'bm.customer_id', '=', 'c.id')
                ->join('warehouses as w', 'bm.gudang_id', '=', 'w.id')
                ->join('type_mobil as tm', 'bm.type_mobil_id', '=', 'tm.id')
                ->leftJoin('barang_keluar_items as bki', function ($join) {
                    $join->on('bmi.barang_masuk_id', '=', 'bki.barang_masuk_id')
                         ->whereColumn('bmi.barang_id', '=', 'bki.barang_id');
                });
                if ($user->warehouse_id !== null) {
                    $barangMasuks = $barangMasuks->where('bm.gudang_id', $user->warehouse_id);
                }
                $barangMasuks = $barangMasuks->groupBy(
                    'bm.id',
                    'bm.tanggal_masuk',
                    'bm.joc_number',
                    'b.nama_barang',
                    'c.name',
                    'w.name',
                    'tm.type',
                    'bm.nomer_polisi',
                    'bm.nomer_container',
                    'bmi.notes',
                    'bmi.unit'
                )
                ->orderBy('bm.tanggal_masuk', 'desc')
                ->get();
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
                'joc_number' => 'nullable|string',
                'tanggal_masuk' => 'required|date',
                'gudang_id' => 'required|exists:warehouses,id',
                'customer_id' => 'required|exists:customers,id',
                'type_mobil_id' => 'required|exists:type_mobil,id',
                'nomer_polisi' => 'nullable|string',
                'nomer_container' => 'nullable|string',
                'harga_simpan_barang' => 'nullable|numeric',
                'harga_lembur' => 'nullable|numeric',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.qty' => 'required|numeric',
                'items.*.unit' => 'required|string',
                'items.*.notes' => 'required|string',
            ]);
            // dd($request->all());

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
            $customer = Customer::find($request->customer_id);
            $tanggalMasuk = $request->tanggal_masuk ? Carbon::createFromFormat('Y-m-d', $request->tanggal_masuk) : null;

            
            if ($customer->type_payment_customer === 'Akhir Bulan') {
                if ($tanggalMasuk && $tanggalMasuk->day > 25) {
                    if ($tanggalMasuk->isLastOfMonth()) {
                        $tanggalPenimbunanMasuk = $tanggalMasuk->copy()->addMonthsNoOverflow(1)->endOfMonth()->format('Y-m-d');
                    } else {
                        $tanggalPenimbunanMasuk = $tanggalMasuk->copy()->addMonth()->endOfMonth()->format('Y-m-d');
                    }
                } else {
                    $tanggalPenimbunanMasuk = $tanggalMasuk ? $tanggalMasuk->endOfMonth()->format('Y-m-d') : null;
                }
            } elseif ($customer->type_payment_customer === 'Pertanggal Masuk') {
                $tanggalPenimbunanMasuk = $tanggalMasuk ? $tanggalMasuk->copy()->addMonth()->subDay()->format('Y-m-d') : null;
            }

            // $tanggalTagihanMasuk = $tanggalMasuk->copy()->addMonthsNoOverflow(1)->startOfMonth()->addDays(2)->format('Y-m-d');
            if ($tanggalMasuk) {
                $tanggalMasukNormal = $request->tanggal_masuk ? Carbon::parse($request->tanggal_masuk) : null;
                if ($tanggalMasukNormal->day <= 2) {
            
                    $tanggalTagihanMasuk = $tanggalMasuk->copy()->startOfMonth()->addDays(2)->format('Y-m-d');
                } else {
                    $tanggalTagihanMasuk = $tanggalMasuk->copy()->addMonthNoOverflow()->startOfMonth()->addDays(2)->format('Y-m-d');
                }
            } else {
                $tanggalTagihanMasuk = null;
            }
            
            
            $barangMasuk = BarangMasuk::create([
                'joc_number' => $request->joc_number ?? $jocNumber,
                'tanggal_masuk' => $request->tanggal_masuk,
                'gudang_id' => $request->gudang_id,
                'customer_id' => $request->customer_id,
                'type_mobil_id' => $request->type_mobil_id, 
                'nomer_polisi' => $request->nomer_polisi ?? "",
                'nomer_container' => $request->nomer_container ?? "", 
                'status_invoice' => "Barang Masuk",
                'harga_simpan_barang' => $request->harga_simpan_barang ?? 0,
                'harga_lembur' => $request->harga_lembur ?? 0,
                'tanggal_tagihan_masuk' => $tanggalTagihanMasuk,
                'tanggal_penimbunan' => $tanggalPenimbunanMasuk
            ]);
            // dd($barangMasuk);

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

            return redirect()->route('data-gudang.barang-masuk.index')->with('success', 'Barang Masuk berhasil disimpan.');
        } catch (\Exception $e) {
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
                'harga_simpan_barang' => 'nullable|numeric',
                'harga_lembur' => 'nullable|numeric',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.qty' => 'required|numeric',
                'items.*.unit' => 'required|string',
                'items.*.notes' => 'nullable|string',
            ]);

            $barangMasuk = BarangMasuk::findOrFail($id);

            $customer = Customer::find($request->customer_id);
            $tanggalMasuk = $request->tanggal_masuk ? Carbon::createFromFormat('Y-m-d', $request->tanggal_masuk) : null;

            if ($customer->type_payment_customer === 'Akhir Bulan') {
                if ($tanggalMasuk && $tanggalMasuk->day > 25) {
                    if ($tanggalMasuk->isLastOfMonth()) {
                        $tanggalPenimbunanMasuk = $tanggalMasuk->copy()->addMonthsNoOverflow(1)->endOfMonth()->format('Y-m-d');
                    } else {
                        $tanggalPenimbunanMasuk = $tanggalMasuk->copy()->addMonth()->endOfMonth()->format('Y-m-d');
                    }
                } else {
                    $tanggalPenimbunanMasuk = $tanggalMasuk ? $tanggalMasuk->endOfMonth()->format('Y-m-d') : null;
                }
            } elseif ($customer->type_payment_customer === 'Pertanggal Masuk') {
                $tanggalPenimbunanMasuk = $tanggalMasuk ? $tanggalMasuk->copy()->addMonth()->subDay()->format('Y-m-d') : null;
            }

            // $tanggalTagihanMasuk = $tanggalMasuk->copy()->addMonthsNoOverflow(1)->startOfMonth()->addDays(2)->format('Y-m-d');
            if ($tanggalMasuk) {
                $tanggalMasukNormal = $request->tanggal_masuk ? Carbon::parse($request->tanggal_masuk) : null;
                if ($tanggalMasukNormal->day <= 2) {
            
                    $tanggalTagihanMasuk = $tanggalMasuk->copy()->startOfMonth()->addDays(2)->format('Y-m-d');
                } else {
                    $tanggalTagihanMasuk = $tanggalMasuk->copy()->addMonthNoOverflow()->startOfMonth()->addDays(2)->format('Y-m-d');
                }
            } else {
                $tanggalTagihanMasuk = null;
            }

            $barangMasuk->update([
                'tanggal_masuk' => $request->tanggal_masuk,
                'gudang_id' => $request->gudang_id,
                'customer_id' => $request->customer_id,
                'type_mobil_id' => $request->type_mobil_id,
                'nomer_polisi' => $request->nomer_polisi ?? "", 
                'nomer_container' => $request->nomer_container ?? "",
                'status_invoice' => "Barang Masuk",
                'harga_simpan_barang' => $request->harga_simpan_barang ?? 0,
                'harga_lembur' => $request->harga_lembur ?? 0,
                'tanggal_tagihan_masuk' => $tanggalTagihanMasuk,
                'tanggal_penimbunan' => $tanggalPenimbunanMasuk,
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
