<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Invoice;
use App\Models\BarangKeluar;

class InvoiceGeneratedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $currentDate = now();

        $barangMasuks = BarangMasuk::where('tanggal_tagihan_masuk', '<=', $currentDate)
            ->where('status_invoice', '<>', 'Invoice Barang Masuk')
            ->get();

        foreach ($barangMasuks as $barangMasuk) {
            // Create a new Invoice for each BarangMasuk
            $invoice = new Invoice();
            $invoice->barang_masuks_id = $barangMasuk->id;
            $invoice->save();

            // Update the status_invoice
            $barangMasuk->status_invoice = 'Invoice Barang Masuk';
            $barangMasuk->save();
        }

        $barangKeluars = BarangKeluar::where('tanggal_tagihan_keluar', '<=', $currentDate)
            ->where('status_invoice', '<>', 'Invoice Barang Keluar')
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereNotNull('harga_kirim_barang')
                        ->where('harga_kirim_barang', '!=', 0);
                })->orWhere(function ($subQuery) {
                    $subQuery->whereNotNull('harga_lembur')
                        ->where('harga_lembur', '!=', 0);
                });
            })
            ->get();

        foreach ($barangKeluars as $barangKeluar) {
            $invoice = new Invoice();
            $invoice->barang_keluars_id = $barangKeluar->id;
            $invoice->save();

            $barangKeluar->status_invoice = 'Invoice Barang Keluar';
            $barangKeluar->save();
        }

        $invoiceMaster = DB::table('invoices')
            ->select(
                'invoices.id',
                'invoices.nomer_invoice',
                'invoices.barang_masuks_id',
                'barang_masuks.joc_number',
                'barang_masuks.tanggal_masuk AS tanggal_masuk_barang',
                'barang_masuks.gudang_id',
                'warehouses_masuks.name AS warehouse_masuk_name',
                'barang_masuks.customer_id',
                'customers_masuks.name AS customer_masuk_name',
                'customers_masuks.type_payment_customer AS type_payment_customer_masuk',
                DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),
                DB::raw('COALESCE(total_keluar.total_qty, 0) AS total_qty_keluar'),
                DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) AS total_sisa'),
                DB::raw('
            CASE
                WHEN COALESCE(total_items.total_qty, 0) = (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0))
                THEN barang_masuks.harga_simpan_barang + (
                    CASE 
                        WHEN customers_masuks.type_payment_customer = "Akhir Bulan" 
                            AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                            AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                        THEN barang_masuks.harga_lembur
                        WHEN customers_masuks.type_payment_customer = "Pertanggal Masuk"
                            AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                        THEN barang_masuks.harga_lembur
                        ELSE 0
                    END
                )
                ELSE ((COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) / COALESCE(total_items.total_qty, 0)) * barang_masuks.harga_simpan_barang + (
                    CASE 
                        WHEN customers_masuks.type_payment_customer = "Akhir Bulan" 
                            AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                            AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                        THEN barang_masuks.harga_lembur
                        WHEN customers_masuks.type_payment_customer = "Pertanggal Masuk"
                            AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                        THEN barang_masuks.harga_lembur
                        ELSE 0
                    END
                )
            END AS total_harga_simpan
        '),
                'barang_masuks.harga_lembur AS harga_lembur_masuk',
                'invoices.barang_keluars_id',
                'barang_keluars.tanggal_keluar',
                'barang_keluars.nomer_surat_jalan',
                'barang_keluars.gudang_id',
                'warehouses_keluars.name AS warehouse_keluar_name',
                'barang_keluars.customer_id',
                'customers_keluars.name AS customer_keluar_name',
                'customers_keluars.type_payment_customer AS type_payment_customer_keluar',
                'barang_keluars.harga_lembur AS harga_lembur_keluar',
                'barang_keluars.harga_kirim_barang'
            )
            ->leftJoin('barang_masuks', 'invoices.barang_masuks_id', '=', 'barang_masuks.id')
            ->leftJoin('barang_keluars', 'invoices.barang_keluars_id', '=', 'barang_keluars.id')
            ->leftJoin('warehouses AS warehouses_masuks', 'barang_masuks.gudang_id', '=', 'warehouses_masuks.id')
            ->leftJoin('customers AS customers_masuks', 'barang_masuks.customer_id', '=', 'customers_masuks.id')
            ->leftJoin('warehouses AS warehouses_keluars', 'barang_keluars.gudang_id', '=', 'warehouses_keluars.id')
            ->leftJoin('customers AS customers_keluars', 'barang_keluars.customer_id', '=', 'customers_keluars.id')
            ->leftJoin(
                DB::raw('(SELECT barang_masuk_id, SUM(qty) AS total_qty FROM barang_masuk_items GROUP BY barang_masuk_id) AS total_items'),
                'barang_masuks.id',
                '=',
                'total_items.barang_masuk_id'
            )
            ->leftJoin(
                DB::raw('(SELECT 
                        bki.barang_masuk_id,
                        SUM(bki.qty) AS total_qty
                  FROM 
                        barang_keluar_items bki
                  JOIN 
                        barang_keluars ON bki.barang_keluar_id = barang_keluars.id
                  WHERE 
                        barang_keluars.tanggal_tagihan_keluar < CURDATE()
                  GROUP BY 
                        bki.barang_masuk_id) AS total_keluar'),
                'barang_masuks.id',
                '=',
                'total_keluar.barang_masuk_id'
            );

        if ($user->warehouse_id) {
            $invoiceMaster = $invoiceMaster->where('barang_keluars.gudang_id', $user->warehouse_id);
        }

        $invoiceMaster = $invoiceMaster->orderBy('barang_keluars.tanggal_keluar', 'desc')->get();

        return view('data-invoice.invoice-master.index', compact('invoiceMaster'));
    }

    public function generateInvoice(Request $request)
{
    $invoiceIds = $request->input('ids');

    if (empty($invoiceIds)) {
        return redirect()->back()->with('error', 'Please select at least one invoice.');
    }

    DB::beginTransaction();
    try {
        $generatedInvoices = [];
        $datePrefix = date('Ymd');

        // Mencari nomor invoice tertinggi yang sudah ada untuk tanggal ini
        $latestInvoice = DB::table('invoices')
            ->select('nomer_invoice')
            ->where('nomer_invoice', 'like', 'ATS/INV/' . $datePrefix . '%')
            ->orderBy('nomer_invoice', 'desc')
            ->first();

        // Mengambil nomor urut yang ada, dan mengubahnya menjadi angka
        if ($latestInvoice) {
            $lastNumber = (int)substr($latestInvoice->nomer_invoice, -4); // Mengambil 4 karakter terakhir
        } else {
            $lastNumber = 0; // Jika belum ada invoice, mulai dari 0
        }

        // Mengambil data invoice berdasarkan ID yang dipilih
        $invoices = DB::table('invoices')->whereIn('id', $invoiceIds)->get();

        foreach ($invoices as $invoice) {
            // Generate nomor invoice baru untuk setiap invoice
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $invoiceNumber = 'ATS/INV/' . $datePrefix . $newNumber;
            $lastNumber++; // Increment nomor urut

            // Update nomor invoice pada tabel invoices
            DB::table('invoices')->where('id', $invoice->id)->update([
                'nomer_invoice' => $invoiceNumber,
            ]);
            DB::table('barang_masuks')->where('id', $invoice->barang_masuks_id)->update([
                'tanggal_tagihan_masuk' => date('Y-m-d'),
            ]);

            // Tambahkan nomor invoice yang baru ke daftar
            $generatedInvoices[] = $invoiceNumber;
        }

        DB::commit();

        return redirect()->back()->with('success', 'Invoices updated: ' . implode(', ', $generatedInvoices));
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Failed to update invoices: ' . $e->getMessage());
    }
}



     

    


    public function show(Request $request)
    {
        $user = Auth::user();
        $nomer_invoice = $request->input('nomer_invoice');

        // Fetch the invoice master data
        $invoiceMaster = DB::table('invoices')
            ->select(
                'invoices.id',
                'invoices.nomer_invoice',
                'invoices.barang_masuks_id',
                'barang_masuks.joc_number',
                'barang_masuks.nomer_polisi AS nomer_polisi_masuk',
                'barang_masuks.nomer_container AS nomer_container_masuk',
                'barang_masuks.tanggal_masuk as tanggal_masuk_barang',
                'barang_masuks.gudang_id',
                'warehouses_masuks.name AS warehouse_masuk_name',
                'barang_masuks.customer_id',
                'customers_masuks.name AS customer_masuk_name',
                'customers_masuks.type_payment_customer AS type_payment_customer_masuk',
                DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),
                DB::raw('COALESCE(total_keluar.total_qty, 0) AS total_qty_keluar'),
                DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) AS total_sisa'),
                DB::raw('(COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) * barang_masuks.harga_simpan_barang AS total_harga_simpan'),
                'barang_masuks.harga_lembur AS harga_lembur_masuk',
                'type_mobil.type AS jenis_mobil_type',

                'invoices.barang_keluars_id',
                'barang_keluars.tanggal_keluar',
                'barang_keluars.nomer_surat_jalan',
                'barang_keluars.gudang_id',
                'warehouses_keluars.name AS warehouse_keluar_name',
                'barang_keluars.customer_id',
                'customers_keluars.name AS customer_keluar_name',
                'customers_keluars.type_payment_customer AS type_payment_customer_keluar',
                'barang_keluars.harga_lembur AS harga_lembur_keluar',
                'barang_keluars.harga_kirim_barang'
            )
            ->leftJoin('barang_masuks', 'invoices.barang_masuks_id', '=', 'barang_masuks.id')
            ->leftJoin('barang_keluars', 'invoices.barang_keluars_id', '=', 'barang_keluars.id')
            ->leftJoin('warehouses AS warehouses_masuks', 'barang_masuks.gudang_id', '=', 'warehouses_masuks.id')
            ->leftJoin('customers AS customers_masuks', 'barang_masuks.customer_id', '=', 'customers_masuks.id')
            ->leftJoin('warehouses AS warehouses_keluars', 'barang_keluars.gudang_id', '=', 'warehouses_keluars.id')
            ->leftJoin('customers AS customers_keluars', 'barang_keluars.customer_id', '=', 'customers_keluars.id')
            ->leftJoin(
                DB::raw('(SELECT barang_masuk_id, SUM(qty) AS total_qty FROM barang_masuk_items GROUP BY barang_masuk_id) AS total_items'),
                'barang_masuks.id',
                '=',
                'total_items.barang_masuk_id'
            )
            ->leftJoin(
                DB::raw('(SELECT barang_keluar_id, SUM(qty) AS total_qty FROM barang_keluar_items GROUP BY barang_keluar_id) AS total_keluar'),
                'barang_masuks.id',
                '=',
                'total_keluar.barang_keluar_id'
            )
            ->leftJoin('type_mobil', 'barang_masuks.type_mobil_id', '=', 'type_mobil.id')
            ->where('invoices.nomer_invoice', $nomer_invoice)
            ->get(); // Fetch all invoices as a collection

        // Filter by warehouse ID if it exists
        if ($user->warehouse_id) {
            $invoiceMaster = $invoiceMaster->where('barang_keluars.gudang_id', $user->warehouse_id);
        }

        // Sort the collection by the 'tanggal_keluar' field in descending order
        $invoiceMaster = $invoiceMaster->sortByDesc('tanggal_keluar'); // or use sortBy depending on your requirement

        return view('data-invoice.invoice-master.show', compact('invoiceMaster'));
    }
}
