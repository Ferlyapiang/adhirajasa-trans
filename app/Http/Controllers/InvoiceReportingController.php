<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Invoice;
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
                'invoices_reporting.id',
                'invoices_reporting.nomer_invoice',
                'invoices_reporting.tanggal_masuk AS tanggal_tagihan',
                'invoices_reporting.barang_masuks_id',
                'barang_masuks.joc_number',
                'invoices_reporting.barang_keluars_id',
                'barang_keluars.nomer_surat_jalan',
                'invoices_reporting.tanggal_masuk_penimbunan',
                'invoices_reporting.tanggal_keluar_penimbunan',
                'invoices_reporting.tanggal_masuk',
                DB::raw('COALESCE(barang_masuks.gudang_id, barang_keluars.gudang_id) AS gudang_id'),
                DB::raw('COALESCE(warehouses_masuks.name, warehouses_keluars.name) AS warehouse_name'),
                DB::raw('COALESCE(barang_masuks.customer_id, barang_keluars.customer_id) AS customer_id'),
                DB::raw('COALESCE(customers_masuks.name, customers_keluars.name) AS customer_name'),

                DB::raw('COALESCE(customers_masuks.type_payment_customer, customers_keluars.type_payment_customer) AS type_payment_customer'),
                DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),
                DB::raw('COALESCE(invoices_reporting.qty, 0) AS total_sisa'),
                'invoices_reporting.harga_lembur',
                'invoices_reporting.harga_simpan_barang',
                'invoices_reporting.harga_kirim_barang'
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



        $invoiceMaster = $invoiceMaster->whereRaw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) > 0 
                OR (
                    (COALESCE(barang_keluars.harga_lembur, 0)) > 0
                    OR (CASE 
                        WHEN customers_masuks.type_payment_customer = "Akhir Bulan" 
                            AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                            AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                        THEN barang_masuks.harga_lembur
                        WHEN customers_masuks.type_payment_customer = "Pertanggal Masuk" 
                            AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                        THEN barang_masuks.harga_lembur
                        ELSE 0
                    END) > 0
                )
                OR COALESCE(barang_keluars.harga_kirim_barang,0) > 0
            ');

        $invoiceMaster = $invoiceMaster->orderBy('invoices_reporting.nomer_invoice', 'desc')->get();
        // dd($invoiceMaster);
        $owners = $invoiceMaster->map(function ($item) {
            return $item->customer_name;
        })
            ->unique()
            ->values();


        return view('data-invoice.invoice-reporting.index', compact('invoiceMaster', 'owners'));
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
            COALESCE(bank_datas_masuk.bank_name, bank_datas_keluar.bank_name) AS bank_name,
            COALESCE(bank_datas_masuk.account_number, bank_datas_keluar.account_number) AS account_number,
            COALESCE(bank_datas_masuk.account_name, bank_datas_keluar.account_name) AS account_name


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
        LEFT JOIN bank_datas AS bank_datas_masuk ON warehouses_masuks.id = bank_datas_masuk.id
        LEFT JOIN bank_datas AS bank_datas_keluar ON warehouses_keluars.id = bank_datas_keluar.id
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
            AND (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) > 0 
            OR (
                COALESCE(barang_keluars.harga_lembur, 0) > 0
                OR (
                    CASE 
                        WHEN customers_masuks.type_payment_customer = 'Akhir Bulan' 
                            AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                            AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                        THEN barang_masuks.harga_lembur
                        WHEN customers_masuks.type_payment_customer = 'Pertanggal Masuk' 
                            AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                        THEN barang_masuks.harga_lembur
                        ELSE 0
                    END
                ) > 0
            )
            OR COALESCE(barang_keluars.harga_kirim_barang, 0) > 0
            ";

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

    public function display()
    {
        // Get data from session
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('alert', 'Waktu login Anda telah habis, silakan login ulang.');
        } else {
            $invoiceMaster = session('invoiceMaster');

            if (empty($invoiceMaster)) {
                return redirect()->route('data-invoice.invoice-reporting.index')->with('error', 'No invoice data available.');
            }

            // dd($invoiceMaster);

            $warehouses = Warehouse::all(); // Get all warehouses
            $headOffice = $warehouses->where('status_office', 'head_office')->first();
            $branchOffices = $warehouses->where('status_office', 'branch_office');
            // Show the view with the invoice data
            return view('data-invoice.invoice-reporting.show', compact('invoiceMaster', 'headOffice', 'branchOffices'));
        }
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


        // Generate PDF
        $pdf = PDF::loadView('data-invoice.invoice-reporting.pdf', compact('invoice', 'invoiceMaster', 'headOffice', 'branchOffices')); // Ensure the view exists
        return $pdf->download('invoice_' . $id . '.pdf');
    }
}