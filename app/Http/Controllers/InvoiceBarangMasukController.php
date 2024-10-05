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
        
        $invoiceMasuk = BarangMasuk::select(
            'barang_masuks.id AS invoice_id',
            'barang_masuks.joc_number',
            'barang_masuks.tanggal_masuk',
            'customers.name AS nama_customer',
            'customers.type_payment_customer',
            'warehouses.name AS nama_gudang',
            'barang_masuks.harga_simpan_barang',
            'barang_masuks.harga_lembur',
            DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),
            DB::raw('COALESCE(total_keluar.total_qty, 0) AS total_qty_keluar'),
            DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) AS total_sisa'),
            DB::raw("
                CASE
                    WHEN COALESCE(total_items.total_qty, 0) = (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) 
                    THEN barang_masuks.harga_simpan_barang + barang_masuks.harga_lembur
                    ELSE ((COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) / COALESCE(total_items.total_qty, 0)) * barang_masuks.harga_simpan_barang + barang_masuks.harga_lembur
                END AS total_harga_simpan
            ")
        )
        ->join('customers', 'barang_masuks.customer_id', '=', 'customers.id')
        ->join('warehouses', 'barang_masuks.gudang_id', '=', 'warehouses.id')
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
                barang_masuk_id,
                SUM(qty) AS total_qty
            FROM 
                barang_keluar_items
            GROUP BY 
                barang_masuk_id
        ) AS total_keluar'), 'barang_masuks.id', '=', 'total_keluar.barang_masuk_id')
        ->where('barang_masuks.status_invoice', 'Barang Masuk');
        
        if ($user->warehouse_id) {
            $invoiceMasuk = $invoiceMasuk->where('barang_masuks.gudang_id', $user->warehouse_id);
        }
    
        $invoiceMasuk = $invoiceMasuk->orderBy('barang_masuks.tanggal_masuk', 'desc')->get();
    
        return view('data-invoice.invoice-masuk.index', compact('invoiceMasuk'));
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
