<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\BarangKeluarItem;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\BankData;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukItem;
use Illuminate\Support\Facades\Auth;
use App\Models\LogData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\JenisMobil;


class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluars = BarangKeluar::with(['gudang', 'customer', 'bankTransfer', 'items.barang'])
            ->orderBy('created_at', 'desc')
            ->get();

        $typeMobilOptions = JenisMobil::all();
        return view('data-gudang.barang-keluar.index', compact('barangKeluars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $warehouses = Warehouse::all();
        if ($user->warehouse_id) {
            $customers = Customer::where('status', 'active')
                                ->where('warehouse_id', $user->warehouse_id)
                                ->get();
        } else {
            $customers = Customer::where('status', 'active')->get();
        }
        $bankTransfers = BankData::all();
        $barangs = Barang::all();
        $typeMobilOptions = JenisMobil::all();
        return view('data-gudang.barang-keluar.create', compact('warehouses', 'customers', 'bankTransfers', 'barangs', 'typeMobilOptions', 'user'));
    }
    
    function generateWarehouseCode($name)
    {
        // Remove non-alphanumeric characters
        $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);

        // Split the name into words
        $words = explode(' ', $cleanName);

        // If it's a single word, take the first three letters
        if (count($words) == 1) {
            $abbreviation = strtoupper(substr($cleanName, 0, 3));
        } else {
            // Take the first letter of each word
            $abbreviation = '';
            foreach ($words as $word) {
                $abbreviation .= strtoupper(substr($word, 0, 1));
            }
            // Return the first 3 characters, or the whole abbreviation if it's shorter
            $abbreviation = substr($abbreviation, 0, 3);
        }

        return $abbreviation;
    }
        public function store(Request $request)
        {
            $request->merge(['items' => json_decode($request->input('items'), true)]);
        
            $validated = $request->validate([
                'tanggal_keluar' => 'required|date',
                'gudang_id' => 'required|exists:warehouses,id',
                'customer_id' => 'required|exists:customers,id',
                'type_mobil_id' => 'nullable|exists:type_mobil,id',
                'nomer_polisi' => 'nullable|string|max:191',
                'nomer_container' => 'nullable|string|max:191',
                'harga_kirim_barang' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'items' => 'required|array',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.no_ref' => 'nullable|string|max:191',
                'items.*.qty' => 'required|integer|min:1',
                'items.*.unit' => 'required|string|max:50',
                'items.*.harga' => 'nullable|numeric|min:0',
                'items.*.total_harga' => 'nullable|numeric|min:0',
                'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
            ]);

            $warehouse = Warehouse::find($validated['gudang_id']);
            if (!$warehouse) {
                return redirect()->back()->with('error', 'Warehouse not found.');
            }
            $warehouseCode = $this->generateWarehouseCode($warehouse->name);

            $bankTransfer = BankData::where('warehouse_id', $validated['gudang_id'])->first();

            $bank_transfer_id = $bankTransfer ? $bankTransfer->id : null;
        
            $year = date('Y');

            $mounth = intval(date('m'));

            

            function intToRoman($number) {
                $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
                $returnValue = '';
                while ($number > 0) {
                    foreach ($map as $roman => $int) {
                        if($number >= $int) {
                            $number -= $int;
                            $returnValue .= $roman;
                            break;
                        }
                    }
                }
                return $returnValue;
            }

            $romanMonth = intToRoman($mounth);
            
            // Find the highest invoice number for the current year and warehouse
            $lastInvoice = BarangKeluar::whereYear('created_at', $year)
                ->where('gudang_id', $validated['gudang_id'])
                ->latest('id')
                ->first();
            
            // Generate the next number for the invoice
            if ($lastInvoice) {
                $lastInvoiceNumber = $lastInvoice->nomer_invoice;
                $lastNumber = (int) substr($lastInvoiceNumber, -3);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
        
            $formattedNumber = sprintf('%03d', $nextNumber);
            $nomer_invoice = "ATS/INV/{$year}/{$romanMonth}/{$warehouseCode}/{$formattedNumber}";
            $nomer_surat_jalan = "SURAT JALAN No. {$formattedNumber}";
        
            $barangKeluarData = [
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'gudang_id' => $validated['gudang_id'],
                'customer_id' => $validated['customer_id'],
                'type_mobil_id' => $validated['type_mobil_id'],
                'nomer_surat_jalan' => $nomer_surat_jalan,
                'nomer_invoice' => $nomer_invoice,
                'nomer_polisi' => $validated['nomer_polisi'],
                'nomer_container' => $validated['nomer_container'],
                'harga_kirim_barang' => $validated['harga_kirim_barang'],
                'bank_transfer_id' => $bank_transfer_id,
            ];
        
            $items = $validated['items'];
        
            try {
                DB::transaction(function () use ($barangKeluarData, $items) {
                    $barangKeluar = BarangKeluar::create($barangKeluarData);
        
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
        
                    LogData::create([
                        'user_id' => Auth::check() ? Auth::id() : null,
                        'name' => Auth::check() ? Auth::user()->name : 'unknown',
                        'action' => 'insert',
                        'details' => 'Created Barang Keluar ID: ' . $barangKeluar->id . ' with data: ' . json_encode($barangKeluarData)
                    ]);
                });
        
                return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar created successfully.');
            } catch (\Exception $e) {
                Log::error('Exception caught:', [
                    'user_id' => Auth::check() ? Auth::id() : 'unknown',
                    'user_name' => Auth::check() ? Auth::user()->name : 'unknown',
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
        
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        }
        
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barangKeluar = BarangKeluar::with('items')->findOrFail($id);

        $warehouses = Warehouse::all();
        $customers = Customer::all();
        $bankTransfers = BankData::all();

        $barangKeluarItems = $barangKeluar->items;
        $barangMasukIds = $barangKeluarItems->pluck('barang_masuk_id')->unique();
        $barangMasukItems = BarangMasukItem::whereIn('barang_masuk_id', $barangMasukIds)->get();
        $groupedBarangMasukItems = $barangMasukItems->groupBy('barang_masuk_id');
        $barangIds = $barangMasukItems->pluck('barang_id')->unique();
        $filteredBarangs = Barang::whereIn('id', $barangIds)->get();
        $barangMasuks = BarangMasuk::whereIn('id', $barangMasukIds)->get()->keyBy('id');

        return view('data-gudang.barang-keluar.detail', [
            'barangKeluar' => $barangKeluar,
            'warehouses' => $warehouses,
            'customers' => $customers,
            'bankTransfers' => $bankTransfers,
            'barangs' => $filteredBarangs,
            'groupedBarangMasukItems' => $groupedBarangMasukItems,
            'barangMasuks' => $barangMasuks,
        ]);
    }


    public function edit($id)
    {
        $user = Auth::user();
        $barangKeluar = BarangKeluar::with('items')->findOrFail($id);

        $warehouses = Warehouse::all();
        if ($user->warehouse_id) {
            $customers = Customer::where('status', 'active')
                                ->where('warehouse_id', $user->warehouse_id)
                                ->get();
        } else {
            $customers = Customer::where('status', 'active')->get();
        }
        $bankTransfers = BankData::all();

        $barangKeluarItems = $barangKeluar->items;

        $barangMasukIds = $barangKeluarItems->pluck('barang_masuk_id')->unique();


        $barangMasukItems = BarangMasukItem::whereIn('barang_masuk_id', $barangMasukIds)->get();

        $groupedBarangMasukItems = $barangMasukItems->groupBy('barang_masuk_id');

        $barangIds = $barangMasukItems->pluck('barang_id')->unique();

        $filteredBarangs = Barang::whereIn('id', $barangIds)->get();

        $barangMasuks = BarangMasuk::whereIn('id', $barangMasukIds)->get()->keyBy('id');

        $typeMobilOptions = JenisMobil::all();
    
        return view('data-gudang.barang-keluar.edit', [
            'barangKeluar' => $barangKeluar,
            'warehouses' => $warehouses,
            'customers' => $customers,
            'bankTransfers' => $bankTransfers,
            'barangs' => $filteredBarangs,
            'groupedBarangMasukItems' => $groupedBarangMasukItems,
            'barangMasuks' => $barangMasuks,
            'typeMobilOptions' => $typeMobilOptions,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['items' => json_decode($request->input('items'), true)]);

        $validated = $request->validate([
            'tanggal_keluar' => 'required|date',
            'gudang_id' => 'required|exists:warehouses,id',
            'customer_id' => 'required|exists:customers,id',
            'type_mobil_id' => 'nullable|exists:type_mobil,id',
            'nomer_surat_jalan' => 'nullable|string|max:191',
            'nomer_invoice' => 'nullable|string|max:191',
            'nomer_polisi' => 'nullable|string|max:191',
            'nomer_container' => 'nullable|string|max:191',
            'bank_transfer_id' => 'nullable|exists:bank_datas,id',
            'harga_kirim_barang' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.no_ref' => 'nullable|string|max:191',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.unit' => 'required|string|max:50',
            'items.*.harga' => 'nullable|numeric|min:0',
            'items.*.total_harga' => 'nullable|numeric|min:0',
            'items.*.barang_masuk_id' => 'required|exists:barang_masuks,id',
        ]);

        $barangKeluarData = [
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'gudang_id' => $validated['gudang_id'],
            'customer_id' => $validated['customer_id'],
            'type_mobil_id' => $validated['type_mobil_id'],
            'nomer_surat_jalan' => $validated['nomer_surat_jalan'],
            'nomer_invoice' => $validated['nomer_invoice'],
            'nomer_polisi' => $validated['nomer_polisi'] ?? null,
            'nomer_container' => $validated['nomer_container'] ?? null,
            'bank_transfer_id' => $validated['bank_transfer_id'],
            'harga_kirim_barang' => $validated['harga_kirim_barang'],
        ];

        $items = $validated['items'];

        try {
            DB::transaction(function () use ($id, $barangKeluarData, $items) {
                $barangKeluar = BarangKeluar::findOrFail($id);
                $barangKeluar->update($barangKeluarData);

                BarangKeluarItem::where('barang_keluar_id', $id)->delete();

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

                LogData::create([
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'name' => Auth::check() ? Auth::user()->name : 'unknown',
                    'action' => 'update',
                    'details' => 'Updated Barang Keluar ID: ' . $barangKeluar->id . ' with data: ' . json_encode($barangKeluarData)
                ]);
            });

            return redirect()->route('data-gudang.barang-keluar.index')->with('success', 'Barang Keluar updated successfully.');
        } catch (\Exception $e) {
            Log::error('Exception caught:', [
                'user_id' => Auth::check() ? Auth::id() : 'unknown',
                'user_name' => Auth::check() ? Auth::user()->name : 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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



    public function getCustomersByWarehouse($warehouse_id)
    {
        $customers = Customer::where('warehouse_id', $warehouse_id)->get();
        return response()->json($customers);
    }
}
