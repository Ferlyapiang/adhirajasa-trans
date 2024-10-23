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
        $currentDate = now();
    
        // Update the status and create an invoice for barang_keluars where applicable
        $barangKeluars = BarangKeluar::where('tanggal_tagihan_keluar', '<=', $currentDate)
            ->where('status_invoice', '<>', 'Invoice Barang Keluar')
            ->where(function($query) {
                $query->where(function($subQuery) {
                    $subQuery->whereNotNull('harga_kirim_barang')
                             ->where('harga_kirim_barang', '!=', 0);
                })->orWhere(function($subQuery) {
                    $subQuery->whereNotNull('harga_lembur')
                             ->where('harga_lembur', '!=', 0);
                });
            })
            ->get();
    
        foreach ($barangKeluars as $barangKeluar) {
            // Create a new Invoice for each BarangKeluar
            $invoice = new Invoice();
            $invoice->barang_keluars_id = $barangKeluar->id; 
            $invoice->save();
    
            // Update the status_invoice
            $barangKeluar->status_invoice = 'Invoice Barang Keluar';
            $barangKeluar->save();
        }
    
        // Build the base query for invoiceKeluar
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
                'bk.tanggal_tagihan_keluar',
                DB::raw('COALESCE(total_keluar.total_qty, 0) AS total_qty')
            )
            ->from('barang_keluars AS bk')
            ->join('customers', 'bk.customer_id', '=', 'customers.id')
            ->join('warehouses', 'bk.gudang_id', '=', 'warehouses.id')
            ->leftJoin(DB::raw('(
                SELECT 
                    bki.barang_keluar_id,
                    SUM(bki.qty) AS total_qty
                FROM 
                    barang_keluar_items AS bki
                GROUP BY 
                    bki.barang_keluar_id
            ) AS total_keluar'), 'bk.id', '=', 'total_keluar.barang_keluar_id') // Use barang_keluar_id in the join
            ->where('bk.status_invoice', 'Barang Keluar')
            ->where(function($query) {
                $query->where(function($subQuery) {
                    $subQuery->whereNotNull('harga_kirim_barang')
                             ->where('harga_kirim_barang', '!=', 0);
                })->orWhere(function($subQuery) {
                    $subQuery->whereNotNull('harga_lembur')
                             ->where('harga_lembur', '!=', 0);
                });
            });
    
        // Apply warehouse filter if applicable
        if (!$user ) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            $invoiceKeluar = $invoiceKeluar->where('bk.gudang_id', $user->warehouse_id);
        }
    
    
        // Order by tanggal_keluar descending
        $invoiceKeluar = $invoiceKeluar->orderBy('bk.tanggal_keluar', 'desc')->get();
    
        // Return view with data
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
