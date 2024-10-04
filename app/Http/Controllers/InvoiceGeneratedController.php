<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InvoiceGeneratedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $invoiceMaster = DB::table('invoices')
            ->select(
                'invoices.id',
                'invoices.nomer_invoice',
                'invoices.barang_masuks_id',
                'barang_masuks.joc_number',
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

        $today = date('Ymd');

        $existingInvoicesCount = DB::table('invoices')
            ->where('nomer_invoice', 'like', 'ATS/INV/' . $today . '%')
            ->count();

        DB::beginTransaction();
        try {
            $generatedInvoices = [];

            $nomerGenerad = 'ATS/INV/' . $today . str_pad($existingInvoicesCount + 1, 3, '0', STR_PAD_LEFT);

            foreach ($invoiceIds as $invoiceId) {
                $invoice = DB::table('invoices')->where('id', $invoiceId)->first();
                if (!empty($invoice->nomer_invoice)) {
                    continue;
                }

                DB::table('invoices')->where('id', $invoiceId)->update([
                    'nomer_invoice' => $nomerGenerad,
                ]);

                $generatedInvoices[] = $nomerGenerad;
            }

            DB::commit();

            return redirect()->back()->with('success', 'Invoices generated: ' . implode(', ', $generatedInvoices));
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to generate invoices: ' . $e->getMessage());
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
