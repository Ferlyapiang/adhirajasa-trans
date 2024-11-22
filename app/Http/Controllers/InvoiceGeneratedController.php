<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\Invoice;
use App\Models\BarangKeluar;
use App\Models\Warehouse;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceGeneratedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $currentDate = now();
        $nextMonth = $currentDate->addMonth();
        if (!$user) {
            return redirect()->route('login')->with('alert', 'You must be logged in to access this page.');
        }

        $barangMasuks = BarangMasuk::where('tanggal_tagihan_masuk', '<=', $nextMonth)
            ->where('status_invoice', '<>', 'Invoice Barang Masuk')
            ->get();

        foreach ($barangMasuks as $barangMasuk) {
            $existingInvoice = Invoice::where('barang_masuks_id', $barangMasuk->id)->first();

            if ($existingInvoice) {
                $existingInvoice->delete();
            }
            $invoice = new Invoice();
            $invoice->barang_masuks_id = $barangMasuk->id;
            $invoice->tanggal_masuk = $barangMasuk->tanggal_tagihan_masuk;
            $invoice->save();
            $barangMasuk->status_invoice = 'Invoice Barang Masuk';
            $barangMasuk->save();
        }

        $barangKeluars = BarangKeluar::where('tanggal_tagihan_keluar', '<=', $nextMonth->addMonth())
            ->where('status_invoice', '<>', 'Invoice Barang Keluar')
            ->get();

        foreach ($barangKeluars as $barangKeluar) {
            $existingInvoice = Invoice::where('barang_keluars_id', $barangKeluar->id)->first();

            if ($existingInvoice) {
                $existingInvoice->delete();
            }

            $invoice = new Invoice();
            $invoice->barang_keluars_id = $barangKeluar->id;
            $invoice->tanggal_masuk = $barangKeluar->tanggal_tagihan_keluar;
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
                'barang_masuks.tanggal_invoice_masuk',
                'barang_masuks.tanggal_invoice_keluar',
                'barang_masuks.tanggal_tagihan_masuk',
                'barang_keluars.tanggal_tagihan_keluar',
                'barang_masuks.tanggal_masuk AS tanggal_masuk_barang',
                'barang_masuks.gudang_id',
                'warehouses_masuks.name AS warehouse_masuk_name',
                'barang_masuks.customer_id',
                'customers_masuks.name AS customer_masuk_name',
                'customers_masuks.type_payment_customer AS type_payment_customer_masuk',
                DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),
                DB::raw('COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0) AS total_qty_keluar'),
                DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0) AS total_sisa'),
                DB::raw('
            CASE
                WHEN COALESCE(total_items.total_qty, 0) = 
                    (COALESCE(total_items.total_qty, 0) - 
                    (COALESCE(total_keluar_invoices.total_qty, 0) + 
                    COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0) + 
                    COALESCE(total_keluar_reporting.total_qty, 0)))
                THEN barang_masuks.harga_simpan_barang
                ELSE 
                    (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0)) / NULLIF(COALESCE(total_items.total_qty, 1), 0) * 
                    barang_masuks.harga_simpan_barang
            END AS total_harga_simpan
        '),
                DB::raw('
            COALESCE(
                CASE 
                    WHEN DATEDIFF(barang_masuks.tanggal_tagihan_masuk, barang_masuks.tanggal_masuk) <= 60
                        AND (invoices.nomer_invoice IS NULL OR invoices.nomer_invoice = "")
                    THEN barang_masuks.harga_lembur
                    ELSE 0
                END, 
            0) AS harga_lembur_masuk
        '),
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
                DB::raw('(
            SELECT barang_masuk_id, SUM(qty) AS total_qty 
            FROM barang_masuk_items 
            GROUP BY barang_masuk_id
        ) AS total_items'),
                'barang_masuks.id',
                '=',
                'total_items.barang_masuk_id'
            )
            ->leftJoin(
                DB::raw('(
            SELECT 
                bki.barang_masuk_id, 
                SUM(
                    CASE 
                        WHEN customers.type_payment_customer = "Akhir Bulan" THEN 
                            CASE 
                                WHEN barang_keluars.tanggal_keluar < barang_masuks.tanggal_invoice_masuk 
                                THEN bki.qty 
                                ELSE 0 
                            END
                        WHEN customers.type_payment_customer = "Pertanggal Masuk" THEN 
                            CASE 
                                WHEN barang_keluars.tanggal_keluar < barang_masuks.tanggal_invoice_masuk 
                                THEN bki.qty 
                                ELSE 0 
                            END
                        ELSE 0
                    END
                ) AS total_qty
            FROM barang_keluar_items bki
            JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
            JOIN barang_masuks ON bki.barang_masuk_id = barang_masuks.id
            JOIN invoices ir ON barang_keluars.id = ir.barang_keluars_id
            JOIN customers ON barang_masuks.customer_id = customers.id
            GROUP BY bki.barang_masuk_id
        ) AS total_keluar_invoices'),
                'barang_masuks.id',
                '=',
                'total_keluar_invoices.barang_masuk_id'
            )
            ->leftJoin(
                DB::raw('(
            SELECT 
                bki.barang_masuk_id, 
                SUM(
                    CASE 
                        WHEN customers.type_payment_customer = "Akhir Bulan" THEN 
                            CASE 
                                WHEN barang_keluars.tanggal_keluar <= barang_masuks.tanggal_invoice_masuk 
                                THEN bki.qty 
                                ELSE 0 
                            END
                        WHEN customers.type_payment_customer = "Pertanggal Masuk" THEN 
                            CASE 
                                WHEN barang_keluars.tanggal_keluar <= barang_masuks.tanggal_invoice_masuk 
                                THEN bki.qty 
                                ELSE 0 
                            END
                        ELSE 0
                    END
                ) AS total_qty_reporting
            FROM 
                barang_keluar_items bki
            JOIN 
                barang_keluars ON bki.barang_keluar_id = barang_keluars.id
            JOIN 
                barang_masuks ON bki.barang_masuk_id = barang_masuks.id
            JOIN 
                customers ON barang_masuks.customer_id = customers.id
            GROUP BY 
                bki.barang_masuk_id
        ) AS total_keluar_invoices_reporting'),
                'barang_masuks.id',
                '=',
                'total_keluar_invoices_reporting.barang_masuk_id'
            )
            ->leftJoin(
                DB::raw('(
            SELECT 	
                bki.barang_masuk_id, 
                SUM(CASE 
                        WHEN barang_keluars.tanggal_keluar < ir.tanggal_masuk_penimbunan
                        THEN bki.qty 
                        ELSE 0 
                    END) AS total_qty
            FROM barang_keluar_items bki
            JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
            JOIN barang_masuks ON bki.barang_masuk_id = barang_masuks.id
            JOIN invoices_reporting ir ON barang_keluars.id = ir.barang_keluars_id
            GROUP BY bki.barang_masuk_id
        ) AS total_keluar_reporting'),
                'barang_masuks.id',
                '=',
                'total_keluar_reporting.barang_masuk_id'
            )
            ->whereNull('invoices.nomer_invoice')
            ->when($user->warehouse_id, function ($query) use ($user) {
                return $query->whereRaw('COALESCE(barang_masuks.gudang_id, barang_keluars.gudang_id) = ?', [$user->warehouse_id]);
            })
            
            ->where('invoices.tanggal_masuk', '<=', DB::raw('LAST_DAY(DATE_ADD(CURDATE(), INTERVAL 2 MONTH))'))
            ->whereRaw('COALESCE(
                    (SELECT min_qties.min_qty
                    FROM invoices_reporting ir
                    JOIN (
                        SELECT barang_masuks_id, MIN(qty) AS min_qty
                        FROM invoices_reporting
                        GROUP BY barang_masuks_id
                    ) AS min_qties 
                    ON ir.barang_masuks_id = min_qties.barang_masuks_id
                    WHERE ir.barang_masuks_id = invoices.barang_masuks_id
                    LIMIT 1), 
                COALESCE(total_items.total_qty, 0)
            )  -  (COALESCE(total_keluar_invoices.total_qty, 0) + COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0)) > 0
        OR (
            (COALESCE(barang_keluars.harga_lembur, 0)) > 0
            OR (CASE 
                WHEN customers_masuks.type_payment_customer = "Akhir Bulan" 
                    AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                    AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                THEN barang_masuks.harga_lembur
                WHEN customers_masuks.type_payment_customer = "Pertanggal Masuk" 
                    AND barang_masuks.tanggal_tagihan_masuk = CURRENT_DATE
                THEN barang_masuks.harga_lembur
                ELSE 0
            END) > 0
        )')
            ->get();

        // dd($invoiceMaster);

        $owners = $invoiceMaster->map(function ($item) {
            return $item->customer_masuk_name ?: $item->customer_keluar_name;
        })->unique()->values();

        $tanggalTagihans = $invoiceMaster->map(function ($item) {
            return $item->tanggal_tagihan_masuk ?? $item->tanggal_tagihan_keluar;
        })->unique()->values();

        foreach ($invoiceMaster as $invoice) {
            $totalSisa = DB::table('invoices')
                ->selectRaw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) AS total_sisa')
                ->leftJoin('barang_masuks', 'invoices.barang_masuks_id', '=', 'barang_masuks.id')
                ->leftJoin(
                    DB::raw('(SELECT barang_masuk_id, SUM(qty) AS total_qty FROM barang_masuk_items GROUP BY barang_masuk_id) AS total_items'),
                    'barang_masuks.id',
                    '=',
                    'total_items.barang_masuk_id'
                )
                ->leftJoin(
                    DB::raw('
                            (
                                SELECT bki.barang_masuk_id, SUM(CASE WHEN  barang_keluars.tanggal_keluar < barang_masuks.tanggal_invoice_keluar THEN bki.qty ELSE 0 END) AS total_qty
                                FROM barang_keluar_items bki
                                JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
                                JOIN barang_masuks ON bki.barang_masuk_id = barang_masuks.id
                                JOIN invoices_reporting ir ON barang_keluars.id = ir.barang_keluars_id
                                GROUP BY bki.barang_masuk_id
                            ) AS total_keluar'),
                    'barang_masuks.id',
                    '=',
                    'total_keluar.barang_masuk_id'
                )
                ->where('invoices.id', $invoice->id)
                ->value('total_sisa');

            DB::table('invoices')
                ->where('id', $invoice->id)
                ->update(['total_qty' => $totalSisa ?? 0]);
        }


        return view('data-invoice.invoice-master.index', compact('invoiceMaster', 'owners', 'tanggalTagihans'));
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

            $date = now();
            $year = $date->format('Y');
            $month = $date->format('m');

            function monthToRoman($monthNumber)
            {
                $romans = [
                    1 => 'I',
                    2 => 'II',
                    3 => 'III',
                    4 => 'IV',
                    5 => 'V',
                    6 => 'VI',
                    7 => 'VII',
                    8 => 'VIII',
                    9 => 'IX',
                    10 => 'X',
                    11 => 'XI',
                    12 => 'XII'
                ];
                return $romans[(int)$monthNumber];
            }

            $monthRoman = monthToRoman($month);

            foreach ($invoiceIds as $invoiceId) {
                $invoice = DB::table('invoices')->where('id', $invoiceId)->first();

                $tanggalMasuk = null;
                $tanggalKeluar = null;
                $jocNumber = null;
                $nomerContainer = null;
                $unit = null;
                $typeMobil = null;
                $tanggalMasukPenimbunan = null;
                $tanggalKeluarPenimbunan = null;
                $tanggalMasukPenimbunanInvoice = null;
                $tanggalKeluarPenimbunanInvoice = null;
                $tanggalMasukPenimbunanInvoiceData = null;
                $tanggalKeluarPenimbunanInvoiceData = null;
                $hargaLembur = null;
                $hargaKirimBarang = null;

                if ($invoice && !empty($invoice->barang_masuks_id)) {
                    $barangMasuk = DB::table('barang_masuks')->where('id', $invoice->barang_masuks_id)->first();
                    if ($barangMasuk) {
                        $tanggalMasuk = $barangMasuk->tanggal_tagihan_masuk ?? null;
                        $tanggalMasukPenimbunan = $barangMasuk->tanggal_invoice_masuk ?? $barangMasuk->tanggal_masuk ?? null;
                        $tanggalKeluarPenimbunan = $barangMasuk->tanggal_invoice_keluar ?? $barangMasuk->tanggal_penimbunan ?? null;
                        $tanggalMasukPenimbunanInvoiceData = $barangMasuk->tanggal_invoice_masuk ?? null;
                        $tanggalKeluarPenimbunanInvoiceData = $barangMasuk->tanggal_invoice_keluar ?? null;
                        $hargaLembur = $barangMasuk->harga_lembur ?? null;

                        $totalQtyKeluarBarang = DB::table('invoices')
                            ->select(
                                'invoices.barang_keluars_id',
                                DB::raw('COALESCE(total_items.total_qty, 0) AS total_qty_masuk'),

                                DB::raw('COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0) AS total_qty_keluar'),

                                DB::raw('COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0) AS total_sisa'),
                                DB::raw('
                                CASE
                                    WHEN COALESCE(total_items.total_qty, 0) = 
                                        (COALESCE(total_items.total_qty, 0) - 
                                        (COALESCE(total_keluar_invoices.total_qty, 0) + 
                                        COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0) + 
                                        COALESCE(total_keluar_reporting.total_qty, 0)))
                                    THEN 
                                        barang_masuks.harga_simpan_barang
                                    ELSE 
                                        (
                                            COALESCE(total_items.total_qty, 0) - 
                                            COALESCE(total_keluar_invoices_reporting.total_qty_reporting, 0)
                                        ) / NULLIF(COALESCE(total_items.total_qty, 1), 0) * 
                                        barang_masuks.harga_simpan_barang
                                END AS total_harga_simpan
                            '),
                            )
                            ->leftJoin('barang_masuks', 'invoices.barang_masuks_id', '=', 'barang_masuks.id')
                            ->leftJoin('barang_keluars', 'invoices.barang_keluars_id', '=', 'barang_keluars.id')
                            ->leftJoin('warehouses AS warehouses_masuks', 'barang_masuks.gudang_id', '=', 'warehouses_masuks.id')
                            ->leftJoin('customers AS customers_masuks', 'barang_masuks.customer_id', '=', 'customers_masuks.id')
                            ->leftJoin('warehouses AS warehouses_keluars', 'barang_keluars.gudang_id', '=', 'warehouses_keluars.id')
                            ->leftJoin('customers AS customers_keluars', 'barang_keluars.customer_id', '=', 'customers_keluars.id')
                            ->leftJoin(
                                DB::raw('(
                                    SELECT barang_masuk_id, SUM(qty) AS total_qty 
                                    FROM barang_masuk_items 
                                    GROUP BY barang_masuk_id
                                ) AS total_items'),
                                'barang_masuks.id',
                                '=',
                                'total_items.barang_masuk_id'
                            )
                            ->leftJoin(
                                DB::raw('(
                                    SELECT 
                                            bki.barang_masuk_id, 
                                            SUM(
                                                CASE 
                                                    WHEN customers.type_payment_customer = "Akhir Bulan" THEN 
                                                        CASE 
                                                            WHEN barang_keluars.tanggal_keluar < barang_masuks.tanggal_invoice_masuk 
                                                            THEN bki.qty 
                                                            ELSE 0 
                                                        END
                                                    WHEN customers.type_payment_customer = "Pertanggal Masuk" THEN 
                                                        CASE 
                                                            WHEN barang_keluars.tanggal_keluar < barang_masuks.tanggal_invoice_masuk 
                                                            THEN bki.qty 
                                                            ELSE 0 
                                                        END
                                                    ELSE 0
                                                END
                                            ) AS total_qty
                                        FROM barang_keluar_items bki
                                        JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
                                        JOIN barang_masuks ON bki.barang_masuk_id = barang_masuks.id
                                        JOIN invoices ir ON barang_keluars.id = ir.barang_keluars_id
                                        JOIN customers ON barang_masuks.customer_id = customers.id
                                        GROUP BY bki.barang_masuk_id
                                    ) AS total_keluar_invoices'),
                                'barang_masuks.id',
                                '=',
                                'total_keluar_invoices.barang_masuk_id'
                            )

                            ->leftJoin(
                                DB::raw('(
                                    SELECT 
                                        bki.barang_masuk_id, 
                                        
                                        SUM(
                                            CASE 
                                                WHEN customers.type_payment_customer = "Akhir Bulan" THEN 
                                                    CASE 
                                                        WHEN barang_keluars.tanggal_keluar <= barang_masuks.tanggal_invoice_masuk 
                                                        THEN bki.qty 
                                                        ELSE 0 
                                                    END
                                                WHEN customers.type_payment_customer = "Pertanggal Masuk" THEN 
                                                    CASE 
                                                        WHEN barang_keluars.tanggal_keluar <= barang_masuks.tanggal_invoice_masuk 
                                                        THEN bki.qty 
                                                        ELSE 0 
                                                    END
                                                ELSE 0
                                            END
                                        ) AS total_qty_reporting
                                    FROM 
                                        barang_keluar_items bki
                                    JOIN 
                                        barang_keluars ON bki.barang_keluar_id = barang_keluars.id
                                    JOIN 
                                        barang_masuks ON bki.barang_masuk_id = barang_masuks.id
                                    JOIN 
                                        customers ON barang_masuks.customer_id = customers.id
                                    GROUP BY 
                                        bki.barang_masuk_id
                                ) AS total_keluar_invoices_reporting'),
                                'barang_masuks.id',
                                '=',
                                'total_keluar_invoices_reporting.barang_masuk_id'
                            )



                            ->leftJoin(
                                DB::raw('(
                                    SELECT 	
                                        bki.barang_masuk_id, 
                                        SUM(CASE 
                                                WHEN barang_keluars.tanggal_keluar < ir.tanggal_masuk_penimbunan
                                                THEN bki.qty 
                                                ELSE 0 
                                            END) AS total_qty
                                    FROM barang_keluar_items bki
                                    JOIN barang_keluars ON bki.barang_keluar_id = barang_keluars.id
                                    JOIN barang_masuks ON bki.barang_masuk_id = barang_masuks.id
                                    JOIN invoices_reporting ir ON barang_keluars.id = ir.barang_keluars_id
                                    GROUP BY bki.barang_masuk_id
                                ) AS total_keluar_reporting'),
                                'barang_masuks.id',
                                '=',
                                'total_keluar_reporting.barang_masuk_id'
                            )

                            ->where('barang_masuks.id', $invoice->barang_masuks_id)
                            ->first();

                        $jocNumber = $barangMasuk->joc_number ?? null;
                        $nomerContainer = $barangMasuk->nomer_container ?: ($barangMasuk->nomer_polisi ?? null);
                        $typeMobil = DB::table('type_mobil')
                            ->where('id', $barangMasuk->type_mobil_id)
                            ->value('type') ?? null;
                        $unit = DB::table('barang_masuk_items')
                            ->where('barang_masuk_id', $invoice->barang_masuks_id)
                            ->value('unit') ?? null;
                    }
                }

                if ($invoice && !empty($invoice->barang_keluars_id)) {
                    $barangKeluar = DB::table('barang_keluars')->where('id', $invoice->barang_keluars_id)->first();

                    if ($barangKeluar) {
                        $tanggalKeluar = $barangKeluar->tanggal_tagihan_keluar ?? null;
                        $jocNumber = $barangKeluar->nomer_surat_jalan ?? null;
                        $nomerContainer = $barangKeluar->nomer_container ?: ($barangKeluar->nomer_polisi ?? null);
                        $tanggalMasukPenimbunanInvoiceData = $barangMasuk->tanggal_invoice_masuk ?? null;
                        $tanggalKeluarPenimbunanInvoiceData = $barangMasuk->tanggal_invoice_keluar ?? null;
                        $typeMobil = DB::table('type_mobil')
                            ->where('id', $barangKeluar->type_mobil_id)
                            ->value('type') ?? null;
                        $unit = DB::table('barang_keluar_items')
                            ->where('barang_keluar_id', $invoice->barang_keluars_id)
                            ->value('unit') ?? null;
                        $hargaLembur = $barangKeluar->harga_lembur ?? null;
                        $hargaKirimBarang = $barangKeluar->harga_kirim_barang ?? null;
                    }
                }

                $tanggalFinal = $tanggalMasuk ?? $tanggalKeluar ?? $invoice->tanggal_masuk ?? null;

                $currentTimestamp = now();

                $latestJoc = DB::table('invoices_reporting')
                    ->where('nomer_invoice', 'like', "ATS/INV/{$year}/{$monthRoman}/%")
                    ->orderBy('nomer_invoice', 'desc')
                    ->first();
                // dd($totalQtyKeluarBarang);
                if ($latestJoc) {
                    $lastInvoiceTime = \Carbon\Carbon::parse($latestJoc->created_at);

                    if ($lastInvoiceTime->isSameMinute($currentTimestamp) && $lastInvoiceTime->second === $currentTimestamp->second) {
                        $nomerGenerad = $latestJoc->nomer_invoice;
                        $reuseInvoice = true;
                    } else {
                        $lastNumber = (int)substr($latestJoc->nomer_invoice, -3);
                        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                        $nomerGenerad = "ATS/INV/{$year}/{$monthRoman}/{$newNumber}";
                    }
                } else {
                    $nomerGenerad = "ATS/INV/{$year}/{$monthRoman}/001";
                }

                DB::table('invoices')->where('id', $invoiceId)->update([
                    'nomer_invoice' => $nomerGenerad,
                    'tanggal_masuk' => $tanggalFinal,
                    'created_at' => $currentTimestamp
                ]);

                $customer = isset($barangMasuk) ? DB::table('customers')->where('id', $barangMasuk->customer_id)->first() : null;
                if ($customer) {
                    if ($customer->type_payment_customer === 'Pertanggal Masuk') {
                        $tanggalMasukPenimbunanInvoice = Carbon::parse($tanggalMasukPenimbunan)->addMonth()->format('Y-m-d') ?? null;
                        $tanggalKeluarPenimbunanInvoice = Carbon::parse($tanggalKeluarPenimbunan)->addMonth()->format('Y-m-d') ?? null;
                    } elseif ($customer->type_payment_customer === 'Akhir Bulan') {
                        $tanggalMasukPenimbunanInvoice = Carbon::parse($tanggalMasuk)->startOfMonth()->format('Y-m-d') ?? null;
                        $tanggalKeluarPenimbunanInvoice = Carbon::parse($tanggalMasuk)->copy()->endOfMonth()->format('Y-m-d') ?? null;
                    }
                }

                // dd($totalQtyKeluarBarang);
                if (DB::table('invoices')->where('id', $invoiceId)->value('nomer_invoice') === $nomerGenerad) {
                    $generatedInvoices[] = $nomerGenerad;

                    DB::table('invoices_reporting')->insert([
                        'nomer_invoice' => $nomerGenerad,
                        'barang_masuks_id' => $invoice->barang_masuks_id ?? null,
                        'barang_keluars_id' => $invoice->barang_keluars_id ?? null,
                        'tanggal_masuk' => $tanggalFinal,
                        'job_number' => $jocNumber ?? null,
                        'nomer_container' => $nomerContainer ?? null,
                        'qty' => $totalQtyKeluarBarang->total_sisa ?? null,
                        'unit' => $unit ?? null,
                        'type_mobil' => $typeMobil ?? null,
                        'diskon' => $invoice->diskon ?? null,
                        'tanggal_masuk_penimbunan' => $tanggalMasukPenimbunanInvoiceData ?? $tanggalMasukPenimbunan ?? null,
                        'tanggal_keluar_penimbunan' => $tanggalKeluarPenimbunanInvoiceData ?? $tanggalKeluarPenimbunan ?? null,
                        'harga_simpan_barang' => $invoice->barang_keluars_id === null
                            ? ($totalQtyKeluarBarang->total_harga_simpan ?? null)
                            : null,

                        'harga_kirim_barang' => $hargaKirimBarang ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if (!empty($hargaLembur) && $hargaLembur > 0) {
                        DB::table('invoices_reporting')->insert([
                            'nomer_invoice' => $nomerGenerad,
                            'barang_masuks_id' => $invoice->barang_masuks_id ?? null,
                            'barang_keluars_id' => $invoice->barang_keluars_id ?? null,
                            'tanggal_masuk' => $tanggalFinal ?? null,
                            'harga_lembur' => $hargaLembur ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }


                    if (!empty($invoice->barang_masuks_id) && isset($barangMasuk->tanggal_tagihan_masuk)) {
                        $currentDate = new \DateTime($barangMasuk->tanggal_tagihan_masuk);

                        if ($currentDate->format('d') == $currentDate->format('t')) {
                            $currentDate->modify('last day of next month');
                        } else {
                            $currentDate->modify('+1 month');
                        }

                        $newDate = $currentDate->format('Y-m-d');
                    } else {
                        $newDate = date('Y-m-d');
                    }

                    DB::table('barang_masuks')->where('id', $invoice->barang_masuks_id ?? null)->update([
                        'tanggal_tagihan_masuk' => $newDate ?? null,
                        'tanggal_invoice_masuk' => $tanggalMasukPenimbunanInvoice ?? null,
                        'tanggal_invoice_keluar' => $tanggalKeluarPenimbunanInvoice ?? null,
                        'status_invoice' => 'Barang Masuk',
                        'harga_lembur' => 0
                    ]);

                    DB::table('invoices')->where('id', $invoiceId)->delete();
                }
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

        // Prepare the SQL query
        $sql = "
        SELECT 
            invoices.id,
            invoices.nomer_invoice,
            invoices.barang_masuks_id,
            invoices.diskon,
            invoices.tanggal_masuk as tanggal_invoice_tagihan,
            barang_masuks.joc_number,
            barang_keluars.nomer_surat_jalan,
            barang_masuks.tanggal_tagihan_masuk,
            barang_keluars.tanggal_tagihan_keluar,
            barang_masuks.tanggal_masuk AS tanggal_masuk_barang,
            barang_masuks.gudang_id AS gudang_masuk,
            warehouses_masuks.name AS warehouse_masuk_name,
            barang_keluars.gudang_id AS gudang_keluar,
            warehouses_keluars.name AS warehouse_keluar_name,
            barang_masuks.customer_id,
            customers_masuks.name AS customer_masuk_name,
            customers_masuks.no_hp AS customer_masuk_no_hp,
            customers_masuks.type_payment_customer AS type_payment_customer_masuk,
            type_mobil_masuk.type AS type_mobil_masuk,
            type_mobil_keluar.type AS type_mobil_keluar,
            barang_masuks.nomer_polisi AS nomer_polisi_masuk,
            barang_keluars.nomer_polisi AS nomer_polisi_keluar,
            barang_masuks.nomer_container AS nomer_container_masuk,
            barang_keluars.nomer_container AS nomer_container_keluar,
            COALESCE(total_items.total_qty, 0) AS total_qty_masuk,
            COALESCE(total_keluar.total_qty, 0) AS total_qty_keluar,
            COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0) AS total_sisa,
            
            CASE
                WHEN COALESCE(total_items.total_qty, 0) = (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0))
                THEN barang_masuks.harga_simpan_barang + (
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
                )
                ELSE ((COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) / COALESCE(total_items.total_qty, 0)) * barang_masuks.harga_simpan_barang + (
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
                )
            END AS total_harga_simpan_lembur,
            CASE
                WHEN COALESCE(total_items.total_qty, 0) = (COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0))
                THEN barang_masuks.harga_simpan_barang
                ELSE ((COALESCE(total_items.total_qty, 0) - COALESCE(total_keluar.total_qty, 0)) / COALESCE(total_items.total_qty, 0)) * barang_masuks.harga_simpan_barang
            END AS total_harga_simpan,
            CASE 
                WHEN customers_masuks.type_payment_customer = 'Akhir Bulan' 
                    AND YEAR(barang_masuks.tanggal_masuk) = YEAR(barang_masuks.tanggal_tagihan_masuk)
                    AND MONTH(barang_masuks.tanggal_masuk) = MONTH(barang_masuks.tanggal_tagihan_masuk)
                THEN barang_masuks.harga_lembur
                WHEN customers_masuks.type_payment_customer = 'Pertanggal Masuk' 
                    AND barang_masuks.tanggal_tagihan_masuk <= DATE_ADD(barang_masuks.tanggal_masuk, INTERVAL 1 MONTH)
                THEN barang_masuks.harga_lembur
                ELSE 0
            END AS harga_lembur_masuk,
            
            invoices.barang_keluars_id,
            barang_keluars.tanggal_keluar,
            barang_keluars.nomer_surat_jalan,
            barang_keluars.gudang_id,
            warehouses_keluars.name AS warehouse_keluar_name,
            barang_keluars.customer_id,
            customers_keluars.name AS customer_keluar_name,
            customers_keluars.type_payment_customer AS type_payment_customer_keluar,
            COALESCE(total_keluar_keluar.total_qty, 0) AS total_qty_keluar_keluar,
            
           COALESCE(
    CASE 
        WHEN customers_keluars.type_payment_customer = 'Akhir Bulan' 
            AND YEAR(barang_keluars.tanggal_keluar) = YEAR(barang_keluars.tanggal_tagihan_keluar)
            AND MONTH(barang_keluars.tanggal_keluar) = MONTH(barang_keluars.tanggal_tagihan_keluar)
        THEN barang_keluars.harga_lembur
        WHEN customers_keluars.type_payment_customer = 'Pertanggal Masuk' 
            AND barang_keluars.tanggal_tagihan_keluar <= DATE_ADD(barang_keluars.tanggal_keluar, INTERVAL 1 MONTH)
        THEN barang_keluars.harga_lembur
        ELSE 0
    END, 0
) AS harga_lembur_keluar,

COALESCE(barang_keluars.harga_kirim_barang, 0) AS harga_kirim_barang,

COALESCE(barang_keluars.harga_lembur, 0) + COALESCE(barang_keluars.harga_kirim_barang, 0) AS total_harga_barang_keluar,
    
customers_masuks.no_npwp AS no_npwp_masuk,
            customers_masuks.no_ktp AS no_ktp_masuk,
            customers_keluars.no_npwp AS no_npwp_keluar,
            customers_keluars.no_ktp AS no_ktp_keluar,
            customers_keluars.no_hp AS customer_keluar_no_hp,
            barang_masuks.tanggal_penimbunan


        FROM invoices
        LEFT JOIN barang_masuks ON invoices.barang_masuks_id = barang_masuks.id
        LEFT JOIN barang_keluars ON invoices.barang_keluars_id = barang_keluars.id
        LEFT JOIN warehouses AS warehouses_masuks ON barang_masuks.gudang_id = warehouses_masuks.id
        LEFT JOIN customers AS customers_masuks ON barang_masuks.customer_id = customers_masuks.id
        LEFT JOIN warehouses AS warehouses_keluars ON barang_keluars.gudang_id = warehouses_keluars.id
        LEFT JOIN customers AS customers_keluars ON barang_keluars.customer_id = customers_keluars.id
        LEFT JOIN (
            SELECT barang_masuk_id, SUM(qty) AS total_qty 
            FROM barang_masuk_items 
            GROUP BY barang_masuk_id
        ) AS total_items ON barang_masuks.id = total_items.barang_masuk_id
        LEFT JOIN (
            SELECT 
                bki.barang_keluar_id,
                SUM(bki.qty) AS total_qty
            FROM 
                barang_keluar_items AS bki
            GROUP BY 
                bki.barang_keluar_id
        ) AS total_keluar_keluar 
        ON barang_keluars.id = total_keluar_keluar.barang_keluar_id
LEFT JOIN 
    (SELECT 
        bki.barang_masuk_id,
        SUM(bki.qty) AS total_qty
     FROM 
        barang_keluar_items bki
     JOIN 
        barang_keluars ON bki.barang_keluar_id = barang_keluars.id
     WHERE 
        barang_keluars.tanggal_tagihan_keluar < CURDATE()
     GROUP BY 
        bki.barang_masuk_id) AS total_keluar ON barang_masuks.id = total_keluar.barang_masuk_id
        LEFT JOIN type_mobil AS type_mobil_masuk ON barang_masuks.type_mobil_id = type_mobil_masuk.id
        LEFT JOIN type_mobil AS type_mobil_keluar ON barang_keluars.type_mobil_id = type_mobil_keluar.id
        WHERE invoices.nomer_invoice = ?
        ORDER BY barang_keluars.tanggal_keluar DESC;
    ";

        $invoiceMaster = DB::select($sql, [$nomer_invoice]);

        if (empty($invoiceMaster)) {

            return redirect()->route('data-invoice.invoice-master.index')->with('error', 'Invoice not found.');
        }


        session(['invoiceMaster' => $invoiceMaster]);

        return redirect()->route('data-invoice.invoice-master.display');
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
                return redirect()->route('data-invoice.invoice-master.index')->with('error', 'No invoice data available.');
            }

            $warehouses = Warehouse::all();
            $headOffice = $warehouses->where('status_office', 'head_office')->first();
            $branchOffices = $warehouses->where('status_office', 'branch_office');

            return view('data-invoice.invoice-master.show', compact('invoiceMaster', 'headOffice', 'branchOffices'));
        }
    }

    public function download($id)
    {
        $invoice = Invoice::find($id);

        $invoiceMaster = session('invoiceMaster');

        if (empty($invoiceMaster)) {
            return redirect()->route('data-invoice.invoice-master.index')->with('error', 'No invoice data available.');
        }

        $warehouses = Warehouse::all();
        $headOffice = $warehouses->where('status_office', 'head_office')->first();
        $branchOffices = $warehouses->where('status_office', 'branch_office');

        $pdf = PDF::loadView('data-invoice.invoice-master.pdf', compact('invoice', 'invoiceMaster', 'headOffice', 'branchOffices')); // Ensure the view exists
        return $pdf->download('invoice_' . $id . '.pdf');
    }
}
