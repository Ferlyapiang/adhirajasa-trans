<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceReporting;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $currentDate = now();
        $invoiceMaster = DB::table('invoices_reporting')
            ->select(
                'invoices_reporting.nomer_invoice',
                DB::raw('MAX(invoices_reporting.tanggal_masuk) AS tanggal_tagihan'),
                DB::raw('GROUP_CONCAT(DISTINCT barang_masuks.joc_number SEPARATOR ", ") AS joc_number'),
                DB::raw('GROUP_CONCAT(DISTINCT barang_keluars.nomer_surat_jalan SEPARATOR ", ") AS nomer_surat_jalan'),
                DB::raw('MAX(invoices_reporting.tanggal_masuk_penimbunan) AS tanggal_masuk_penimbunan'),
                DB::raw('MAX(invoices_reporting.tanggal_keluar_penimbunan) AS tanggal_keluar_penimbunan'),
                DB::raw('MAX(invoices_reporting.tanggal_masuk) AS tanggal_masuk'),
                DB::raw('COALESCE(MAX(barang_masuks.gudang_id), MAX(barang_keluars.gudang_id)) AS gudang_id'),
                DB::raw('COALESCE(MAX(warehouses_masuks.name), MAX(warehouses_keluars.name)) AS warehouse_name'),
                DB::raw('COALESCE(MAX(barang_masuks.customer_id), MAX(barang_keluars.customer_id)) AS customer_id'),
                DB::raw('COALESCE(MAX(customers_masuks.name), MAX(customers_keluars.name)) AS customer_name'),
                DB::raw('COALESCE(MAX(customers_masuks.type_payment_customer), MAX(customers_keluars.type_payment_customer)) AS type_payment_customer'),
                DB::raw('COALESCE(MAX(total_items.total_qty), 0) AS total_qty_masuk'),
                DB::raw('COALESCE(MAX(invoices_reporting.qty), 0) AS total_sisa'),
                DB::raw('COALESCE(MAX(invoices_reporting.harga_lembur), 0) AS harga_lembur'),
                DB::raw('MAX(invoices_reporting.harga_simpan_barang) AS harga_simpan_barang'),
                DB::raw('MAX(invoices_reporting.harga_kirim_barang) AS harga_kirim_barang')
            )
            ->leftJoin('barang_masuks', 'invoices_reporting.barang_masuks_id', '=', 'barang_masuks.id')
            ->leftJoin('barang_keluars', 'invoices_reporting.barang_keluars_id', '=', 'barang_keluars.id')
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
                DB::raw('(SELECT bki.barang_masuk_id, SUM(bki.qty) AS total_qty
                        FROM barang_keluar_items bki
                        JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
                        WHERE barang_keluars.tanggal_tagihan_keluar < CURDATE()
                        GROUP BY bki.barang_masuk_id) AS total_keluar'),
                'barang_masuks.id',
                '=',
                'total_keluar.barang_masuk_id'
            );

        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            $invoiceMaster = $invoiceMaster->where('barang_keluars.gudang_id', $user->warehouse_id);
        }


        $invoiceMaster = $invoiceMaster->whereRaw('
                COALESCE(invoices_reporting.harga_lembur, 0) > 0 
                OR COALESCE(invoices_reporting.qty, 0) > 0
            ');

        $invoiceMaster = $invoiceMaster->groupBy('invoices_reporting.nomer_invoice');
        $invoiceMaster = $invoiceMaster->orderBy('invoices_reporting.nomer_invoice', 'desc')->get();

        $owners = $invoiceMaster->map(function ($item) {
            return $item->customer_name;
        })
            ->unique()
            ->values();

        $tanggalTagihans = $invoiceMaster->map(function ($item) {
            return $item->tanggal_tagihan;
        })
            ->unique()
            ->values();


        return view('data-invoice.invoice-reporting.index', compact('invoiceMaster', 'owners', 'tanggalTagihans'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'nomer_invoice' => 'required|string',
        ]);

        try {
            // Hapus data berdasarkan nomor invoice
            DB::table('invoices_reporting')->where('nomer_invoice', $request->nomer_invoice)->delete();

            // Redirect dengan pesan sukses
            return redirect()->route('data-invoice.invoice-reporting.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            // Redirect dengan pesan error jika ada masalah
            return redirect()->route('data-invoice.invoice-reporting.index')->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }



    public function show(Request $request)
    {
        $user = Auth::user();
        $nomer_invoice = $request->input('nomer_invoice');

        // Prepare the SQL query
        $sql = "
        SELECT 
            invoices_reporting.id,
            invoices_reporting.nomer_invoice,
            invoices_reporting.tanggal_masuk AS tanggal_tagihan,
            invoices_reporting.barang_masuks_id,
            barang_masuks.joc_number,
            invoices_reporting.barang_keluars_id,
            barang_keluars.nomer_surat_jalan,
            barang_keluars.address,
            invoices_reporting.tanggal_masuk_penimbunan,
            invoices_reporting.tanggal_keluar_penimbunan,
            invoices_reporting.tanggal_masuk,
            COALESCE(customers_masuks.no_npwp, customers_keluars.no_npwp) AS customer_no_npwp,
            COALESCE(customers_masuks.no_ktp, customers_keluars.no_ktp) AS customer_no_ktp,
            
            COALESCE(barang_masuks.gudang_id, barang_keluars.gudang_id) AS gudang_id,
            COALESCE(warehouses_masuks.name, warehouses_keluars.name) AS warehouse_name,
            
            COALESCE(barang_masuks.customer_id, barang_keluars.customer_id) AS customer_id,
            COALESCE(customers_masuks.name, customers_keluars.name) AS customer_name,
            COALESCE(customers_masuks.address, customers_keluars.address) AS customer_address,
            
            -- Unified payment type field
            COALESCE(customers_masuks.type_payment_customer, customers_keluars.type_payment_customer) AS type_payment_customer,
            
            -- Quantity and price calculations
            COALESCE(total_items.total_qty, 0) AS total_qty_masuk,
            COALESCE(invoices_reporting.qty, 0) AS total_sisa,
            invoices_reporting.harga_lembur,
            invoices_reporting.harga_simpan_barang,
            invoices_reporting.harga_kirim_barang,
            COALESCE(customers_masuks.no_hp, customers_keluars.no_hp) AS customer_no_hp,
            COALESCE(barang_masuks.nomer_polisi, barang_keluars.nomer_polisi) AS nomer_polisi,
            COALESCE(barang_masuks.nomer_container, barang_keluars.nomer_container) AS nomer_container, 
            COALESCE(type_mobil_masuk.type, type_mobil_keluar.type) AS type_mobil,
             CASE 
                WHEN COALESCE(customers_masuks.no_npwp, customers_keluars.no_npwp) IS NOT NULL 
                AND bank_datas_masuk.status_bank = 'PT' THEN bank_datas_masuk.bank_name
                WHEN COALESCE(customers_masuks.no_ktp, customers_keluars.no_ktp) IS NOT NULL 
                AND bank_datas_masuk.status_bank = 'Pribadi' THEN bank_datas_masuk.bank_name
                WHEN COALESCE(customers_masuks.no_npwp, customers_keluars.no_npwp) IS NOT NULL 
                AND bank_datas_keluar.status_bank = 'PT' THEN bank_datas_keluar.bank_name
                WHEN COALESCE(customers_masuks.no_ktp, customers_keluars.no_ktp) IS NOT NULL 
                AND bank_datas_keluar.status_bank = 'Pribadi' THEN bank_datas_keluar.bank_name
                ELSE NULL
            END AS bank_name,
            COALESCE(bank_datas_masuk.account_number, bank_datas_keluar.account_number) AS account_number,
    COALESCE(bank_datas_masuk.account_name, bank_datas_keluar.account_name) AS account_name,
            invoices_reporting.diskon,
            invoices_reporting.noted,
            invoices_reporting.rokok,
            invoices_reporting.notedRokok


        FROM 
            invoices_reporting
        LEFT JOIN 
            barang_masuks ON invoices_reporting.barang_masuks_id = barang_masuks.id
        LEFT JOIN 
            barang_keluars ON invoices_reporting.barang_keluars_id = barang_keluars.id
        LEFT JOIN 
            warehouses AS warehouses_masuks ON barang_masuks.gudang_id = warehouses_masuks.id
        LEFT JOIN 
            customers AS customers_masuks ON barang_masuks.customer_id = customers_masuks.id
        LEFT JOIN 
            warehouses AS warehouses_keluars ON barang_keluars.gudang_id = warehouses_keluars.id
        LEFT JOIN 
            customers AS customers_keluars ON barang_keluars.customer_id = customers_keluars.id
        LEFT JOIN 
            bank_datas AS bank_datas_masuk ON warehouses_masuks.id = bank_datas_masuk.warehouse_id 
            AND (
                (COALESCE(customers_masuks.no_npwp, customers_keluars.no_npwp) IS NOT NULL AND bank_datas_masuk.status_bank = 'PT') OR
                (COALESCE(customers_masuks.no_ktp, customers_keluars.no_ktp) IS NOT NULL AND bank_datas_masuk.status_bank = 'Pribadi')
            )

        LEFT JOIN 
            bank_datas AS bank_datas_keluar ON warehouses_keluars.id = bank_datas_keluar.warehouse_id 
            AND (
                (COALESCE(customers_masuks.no_npwp, customers_keluars.no_npwp) IS NOT NULL AND bank_datas_keluar.status_bank = 'PT') OR
                (COALESCE(customers_masuks.no_ktp, customers_keluars.no_ktp) IS NOT NULL AND bank_datas_keluar.status_bank = 'Pribadi')
            )
        LEFT JOIN (
            SELECT 
                barang_masuk_id, SUM(qty) AS total_qty 
            FROM 
                barang_masuk_items 
            GROUP BY 
                barang_masuk_id
        ) AS total_items ON barang_masuks.id = total_items.barang_masuk_id
        LEFT JOIN type_mobil AS type_mobil_masuk ON barang_masuks.type_mobil_id = type_mobil_masuk.id
        LEFT JOIN type_mobil AS type_mobil_keluar ON barang_keluars.type_mobil_id = type_mobil_keluar.id
        LEFT JOIN (
            SELECT bki.barang_masuk_id, SUM(bki.qty) AS total_qty
            FROM barang_keluar_items bki
            JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
            WHERE barang_keluars.tanggal_tagihan_keluar < CURDATE()
            GROUP BY bki.barang_masuk_id
        ) AS total_keluar ON barang_masuks.id = total_keluar.barang_masuk_id
        WHERE 
            invoices_reporting.nomer_invoice = ?
           AND (
                COALESCE(invoices_reporting.harga_lembur, 0) > 0
                OR COALESCE(invoices_reporting.harga_simpan_barang, 0) > 0
                OR COALESCE(invoices_reporting.harga_kirim_barang, 0) > 0
                OR COALESCE(invoices_reporting.rokok, 0) > 0
            )";

        // Execute the SQL query with the provided invoice number
        $invoiceMaster = DB::select($sql, [$nomer_invoice]);

        if (empty($invoiceMaster)) {

            return redirect()->route('data-invoice.invoice-reporting.index')->with('error', 'Invoice not found.');
        }

        // dd($invoiceMaster);

        session([
            'invoiceMaster' => $invoiceMaster
        ]);

        // return view('data-invoice.invoice-master.show', compact('invoiceMaster'));
        return redirect()->route('data-invoice.invoice-reporting.display');
    }

    public function updateInvoice(Request $request)
    {
        $request->validate([
            'nomer_invoice' => 'required',
            'new_nomer_invoice' => 'required',
            'new_tanggal_masuk' => 'required|date',
        ]);

        $nomer_invoice = $request->input('nomer_invoice'); // current invoice number
        $new_nomer_invoice = $request->input('new_nomer_invoice'); // new invoice number
        $new_tanggal_masuk = $request->input('new_tanggal_masuk'); // new transaction date

        // Update the record with the new values
        DB::table('invoices_reporting')
            ->where('nomer_invoice', $nomer_invoice)
            ->update([
                'nomer_invoice' => $new_nomer_invoice,
                'tanggal_masuk' => $new_tanggal_masuk,
            ]);

        return redirect()->route('data-invoice.invoice-reporting.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function download($id)
    {
        $invoice = Invoice::find($id); // Replace with your actual model and logic

        $invoiceMaster = session('invoiceMaster');

        if (empty($invoiceMaster)) {
            return redirect()->route('data-invoice.invoice-reporting.index')->with('error', 'No invoice data available.');
        }

        $warehouses = Warehouse::all(); // Get all warehouses
        $headOffice = $warehouses->where('status_office', 'head_office')->first();
        $branchOffices = $warehouses->where('status_office', 'branch_office');
        $nomer_invoice = $invoiceMaster[0]->nomer_invoice;

        // Retrieve both total discount and concatenated noted
        $invoiceSummary = $this->getInvoiceSummary($nomer_invoice);


        // Generate PDF
        $pdf = PDF::loadView('data-invoice.invoice-reporting.pdf', compact('invoice', 'invoiceMaster', 'headOffice', 'branchOffices', 'invoiceSummary')); // Ensure the view exists
        return $pdf->download('invoice_' . $id . '.pdf');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'diskon' => 'nullable|integer|min:0',
            'noted' => 'nullable|string',
        ]);

        $invoice = InvoiceReporting::findOrFail($id);
        $invoice->diskon = $request->input('diskon');
        $invoice->noted = $request->input('noted');
        $invoice->save();

        return redirect()->route('data-invoice.invoice-reporting.display')
            ->with('success', 'Invoice updated successfully.');
    }
    public function display()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        }

        $invoiceMaster = session('invoiceMaster');

        if (empty($invoiceMaster)) {
            return redirect()->route('data-invoice.invoice-reporting.index')->with('error', 'No invoice data available.');
        }

        $nomer_invoice = $invoiceMaster[0]->nomer_invoice;

        // Retrieve both total discount and concatenated noted
        $invoiceSummary = $this->getInvoiceSummary($nomer_invoice);
        $warehouses = Warehouse::all();
        $headOffice = $warehouses->where('status_office', 'head_office')->first();
        $branchOffices = $warehouses->where('status_office', 'branch_office');

        // Retrieve the data for 'rokok' and 'notedRokok' from the invoices_reporting table
        $invoiceDetails = DB::table('invoices_reporting')
            ->where('nomer_invoice', $nomer_invoice)
            ->first();

        // Pass the data to the view, including invoiceSummary and the invoiceDetails
        return view('data-invoice.invoice-reporting.show', [
            'invoiceMaster' => $invoiceMaster,
            'headOffice' => $headOffice,
            'branchOffices' => $branchOffices,
            'totalDiscount' => $invoiceSummary->total_diskon,
            'reportNoted' => $invoiceSummary->concatenated_noted,
            'invoiceSummary' => $invoiceSummary,
            'invoiceDetails' => $invoiceDetails, // Include the invoice details
        ]);
    }



    public function addDiscountAndNote(Request $request)
    {
        $request->validate([
            'nomer_invoice' => 'required|string|exists:invoices_reporting,nomer_invoice',
            'diskon' => 'nullable|integer',
            'noted' => 'nullable|string',
        ]);

        DB::table('invoices_reporting')->insert([
            'nomer_invoice' => $request->nomer_invoice,
            'diskon' => $request->diskon ?? 0,
            'noted' => $request->noted,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data-invoice.invoice-reporting.display')
            ->with('success', 'Diskon dan noted berhasil ditambahkan.');
    }

    public function deleteDiscount($id)
    {
        DB::table('invoices_reporting')->where('id', $id)->delete();
        // dd($id);
        return redirect()->route('data-invoice.invoice-reporting.display')
            ->with('success', 'Diskon dan noted berhasil dihapus.');
    }

    public function getInvoiceSummary($nomer_invoice)
    {
        // Query to calculate total discount and concatenate noted, grouped by 'id', ordered by the highest 'id'
        $summary = DB::table('invoices_reporting')
            ->select(
                'id',
                DB::raw('SUM(diskon) AS total_diskon'),
                DB::raw('GROUP_CONCAT(noted SEPARATOR ", ") AS concatenated_noted')
            )
            ->where('nomer_invoice', $nomer_invoice)
            ->groupBy('id')
            ->orderByDesc('id')
            ->limit(1)
            ->first();

        return $summary;
    }


    public function addRokokAndNote(Request $request)
    {
        $request->validate([
            'nomer_invoice' => 'required|string|exists:invoices_reporting,nomer_invoice',
            'rokok' => 'nullable|integer',
            'notedRokok' => 'nullable|string',
        ]);

        DB::table('invoices_reporting')->insert([
            'nomer_invoice' => $request->nomer_invoice,
            'rokok' => $request->rokok ?? 0,
            'notedRokok' => $request->notedRokok,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('data-invoice.invoice-reporting.index')
            ->with('success', 'Rokok dan notedRokok berhasil ditambahkan.');
    }
    // Delete ALL
    public function deleteAllRokokAndNote(Request $request)
    {
        $request->validate([
            'nomer_invoice' => 'required|string|exists:invoices_reporting,nomer_invoice',
        ]);

        // Check if 'rokok' and 'notedRokok' are not null
        DB::table('invoices_reporting')
            ->where('nomer_invoice', $request->nomer_invoice)
            ->whereNotNull('rokok')
            ->whereNotNull('notedRokok')
            ->delete(); // Delete only the row with non-null 'rokok' and 'notedRokok'

        return redirect()->route('data-invoice.invoice-reporting.index')
            ->with('success', 'Rokok dan notedRokok berhasil dihapus.');
    }

    // delete 1 1
    public function deleteRokokAndNote(Request $request)
    {
        $request->validate([
            'nomer_invoice' => 'required|string|exists:invoices_reporting,nomer_invoice',
        ]);

        // Get the most recent record (the latest one)
        $latestRecord = DB::table('invoices_reporting')
            ->where('nomer_invoice', $request->nomer_invoice)
            ->orderBy('updated_at', 'desc') // Order by latest updated_at
            ->first(); // Get the most recent row

        if ($latestRecord) {
            // Delete that specific record
            DB::table('invoices_reporting')
                ->where('id', $latestRecord->id)
                ->delete();
        }

        return redirect()->route('data-invoice.invoice-reporting.index')
            ->with('success', 'Rokok dan notedRokok berhasil dihapus.');
    }
}
