<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceBarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $user = Auth::user();
    
        $invoiceKeluar = BarangKeluar::select(
            'bk.id AS barang_keluar_id',
            'bk.tanggal_keluar',
            'customers.name AS nama_customer',
            'customers.type_payment_customer',
            'warehouses.name AS nama_gudang',
            'bk.type_mobil_id',
            'bk.nomer_surat_jalan',
            'bk.nomer_invoice',
            'bk.nomer_polisi',
            'bk.nomer_container',
            'bk.harga_kirim_barang',
            'bk.bank_transfer_id',
            'bk.harga_lembur',
            'bk.status_invoice',
            DB::raw('COALESCE(total_keluar.total_qty, 0) AS total_qty')
        )
        ->from('barang_keluars AS bk')
        ->join('customers', 'bk.customer_id', '=', 'customers.id')
        ->join('warehouses', 'bk.gudang_id', '=', 'warehouses.id')
        ->leftJoin(DB::raw('(
            SELECT 
                barang_masuk_id,
                SUM(qty) AS total_qty
            FROM 
                barang_keluar_items
            GROUP BY 
                barang_masuk_id
        ) AS total_keluar'), 'bk.id', '=', 'total_keluar.barang_masuk_id')
        ->where('bk.status_invoice', 'Barang Keluar');
    
        // Apply warehouse filter if applicable
        if ($user->warehouse_id) {
            $invoiceKeluar = $invoiceKeluar->where('bk.gudang_id', $user->warehouse_id);
        }
    
        $invoiceKeluar = $invoiceKeluar->orderBy('bk.tanggal_keluar', 'desc')->get();
    
        return view('data-invoice.invoice-keluar.index', compact('invoiceKeluar'));
    }
    
    
    public function updateStatus(Request $request) {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:barang_keluars,id', 
        ]);
        // dd($request->ids);
    
        foreach ($request->ids as $id) {
            $invoice = new Invoice();
            $invoice->barang_keluars_id = $id; 
            $invoice->save();
        }
    
        BarangKeluar::whereIn('id', $request->ids)->update(['status_invoice' => 'Invoice Barang Keluar']);
        
        return response()->json(['message' => 'Status updated successfully']);
    }
    
    
}
