<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\BarangMasuk;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceBarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $user = Auth::user();
        $currentDate = now();

        $barangMasuks = BarangMasuk::where('tanggal_tagihan_masuk', '<=', $currentDate)
            ->where('status_invoice', '<>', 'Invoice Barang Masuk')
            ->get();
    
        foreach ($barangMasuks as $barangMasuk) {
            // Create a new Invoice for each BarangMasuk
            $invoice = new Invoice();
            $invoice->barang_masuks_id = $barangMasuk->id; 
            $invoice->tanggal_masuk = $barangMasuk->tanggal_tagihan_masuk;
            $invoice->save();
    
            // Update the status_invoice
            $barangMasuk->status_invoice = 'Invoice Barang Masuk';
            $barangMasuk->save();
        }
    
        $invoiceMasuk = BarangMasuk::select(
            'barang_masuks.id AS invoice_id',
            'barang_masuks.joc_number',
            'barang_masuks.nomer_polisi',
            'barang_masuks.nomer_container',
            'type_mobil.type',
            'barang_masuks.tanggal_masuk',
            'barang_masuks.tanggal_tagihan_masuk',
            'customers.name AS nama_customer',
            'customers.type_payment_customer',
            'warehouses.name AS nama_gudang',
            'barang_masuks.harga_simpan_barang',
    
            // CASE logic for harga_lembur
            DB::raw("
                CASE 
                    WHEN customers.type_payment_customer = 'Akhir Bulan' 
                        AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                        AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                    THEN barang_masuks.harga_lembur
                    WHEN customers.type_payment_customer = 'Pertanggal Masuk' 
                        AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                    THEN barang_masuks.harga_lembur
                    ELSE 0
                END AS harga_lembur
            "),
    
            DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),
            DB::raw('COALESCE(total_keluar.total_qty, 0) AS total_qty_keluar'),
            DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) AS total_sisa'),
    
            // CASE logic for total_harga_simpan
            DB::raw("
                CASE
                    WHEN COALESCE(total_items.total_qty, 0) = (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0))
                    THEN barang_masuks.harga_simpan_barang + (
                        CASE 
                            WHEN customers.type_payment_customer = 'Akhir Bulan' 
                                AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                                AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                            THEN barang_masuks.harga_lembur
                            WHEN customers.type_payment_customer = 'Pertanggal Masuk'
                                AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                            THEN barang_masuks.harga_lembur
                            ELSE 0
                        END
                    )
                    ELSE ((COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) / COALESCE(total_items.total_qty, 0)) * barang_masuks.harga_simpan_barang + (
                        CASE 
                            WHEN customers.type_payment_customer = 'Akhir Bulan' 
                                AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                                AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                            THEN barang_masuks.harga_lembur
                            WHEN customers.type_payment_customer = 'Pertanggal Masuk'
                                AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                            THEN barang_masuks.harga_lembur
                            ELSE 0
                        END
                    )
                END AS total_harga_simpan
            ")
        )
        ->join('customers', 'barang_masuks.customer_id', '=', 'customers.id')
        ->join('warehouses', 'barang_masuks.gudang_id', '=', 'warehouses.id')
        ->join('type_mobil', 'barang_masuks.type_mobil_id', '=', 'type_mobil.id')
        ->leftJoin(DB::raw('(
            SELECT 
                barang_masuk_id,
                SUM(qty) AS total_qty
            FROM 
                barang_masuk_items
            GROUP BY 
                barang_masuk_id
        ) AS total_items'), 'barang_masuks.id', '=', 'total_items.barang_masuk_id')
        ->leftJoin(DB::raw('(
            SELECT 
                bki.barang_masuk_id,
                SUM(bki.qty) AS total_qty
            FROM 
                barang_keluar_items bki
            JOIN 
                barang_keluars ON bki.barang_keluar_id = barang_keluars.id
            WHERE 
                barang_keluars.tanggal_tagihan_keluar < CURDATE()
            GROUP BY 
                bki.barang_masuk_id
        ) AS total_keluar'), 'barang_masuks.id', '=', 'total_keluar.barang_masuk_id')
        ->where('barang_masuks.status_invoice', 'Barang Masuk')
        // ->where('barang_masuks.tanggal_tagihan_masuk', '<=', \Carbon\Carbon::now()->endOfMonth())
        ->where(DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)'), '<>', 0);
    
        // Filter berdasarkan warehouse user jika ada
        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } 
        if ($user->warehouse_id === null) {
        } else {
            $invoiceMasuk = $invoiceMasuk->where('barang_masuks.gudang_id', $user->warehouse_id);
        }
        
        $owners = $invoiceMasuk->pluck('nama_customer')
            ->unique()
            ->values();

        $invoiceMasuk = $invoiceMasuk->orderBy('barang_masuks.tanggal_masuk', 'desc')->get();
    
        return view('data-invoice.invoice-masuk.index', compact('invoiceMasuk', 'owners'));
    }
    
    
    
    
        
    public function updateStatus(Request $request) {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:barang_masuks,id', 
        ]);
        // dd($request->ids);
    
        foreach ($request->ids as $id) {
            $invoice = new Invoice();
            $invoice->barang_masuks_id = $id; 
            $invoice->save();
        }
    
        BarangMasuk::whereIn('id', $request->ids)->update(['status_invoice' => 'Invoice Barang Masuk']);
        
        return response()->json(['message' => 'Status updated successfully']);
    }
    
    
}
