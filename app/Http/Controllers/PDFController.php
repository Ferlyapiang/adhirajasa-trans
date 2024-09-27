<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Warehouse;
use App\Models\Customer;
use App\Models\BankData;
use App\Models\BarangMasukItem;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\JenisMobil;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function BarangKeluar_download_pdf($id)
    {
        // Fetching the barangKeluar data with related items
        $barangKeluar = BarangKeluar::with('items', 'customer')->findOrFail($id);

        // Fetch related data
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

        // Get the customer name from the related customer model
        $customerName = $barangKeluar->customer->name ?? 'Unknown_Customer'; // Use 'Unknown_Customer' if the name is missing

        // Prepare the data for the PDF
        $data = [
            'barangKeluar' => $barangKeluar,
            'warehouses' => $warehouses,
            'customers' => $customers,
            'bankTransfers' => $bankTransfers,
            'barangs' => $filteredBarangs,
            'groupedBarangMasukItems' => $groupedBarangMasukItems,
            'barangMasuks' => $barangMasuks,
        ];

        // Load the PDF view and pass the data to it
        $pdf = Pdf::loadView('pdf.invoice-barang-keluar', $data);

        // Return the generated PDF for download, using the customer name in the filename
        return $pdf->download('invoice_tanpa_pajak_' . str_replace(' ', '_', $customerName) . '.pdf');
    }

    public function BarangKeluar_pajak_download_pdf($id)
    {
        // Fetching the barangKeluar data with related items
        $barangKeluar = BarangKeluar::with('items', 'customer')->findOrFail($id);

        // Fetch related data
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

        // Get the customer name from the related customer model
        $customerName = $barangKeluar->customer->name ?? 'Unknown_Customer'; // Use 'Unknown_Customer' if the name is missing

        // Prepare the data for the PDF
        $data = [
            'barangKeluar' => $barangKeluar,
            'warehouses' => $warehouses,
            'customers' => $customers,
            'bankTransfers' => $bankTransfers,
            'barangs' => $filteredBarangs,
            'groupedBarangMasukItems' => $groupedBarangMasukItems,
            'barangMasuks' => $barangMasuks,
        ];

        // Load the PDF view and pass the data to it
        $pdf = Pdf::loadView('pdf.invoice-barang-keluar-pajak', $data);

        // Return the generated PDF for download, using the customer name in the filename
        return $pdf->download('invoice_pajak_' . str_replace(' ', '_', $customerName) . '.pdf');
    }

    public function downloadSuratJalanPDF($id)
    {
        $barangKeluar = BarangKeluar::with('items')->findOrFail($id);
        $warehouses = Warehouse::all();
        $customers = Customer::all();
        $bankTransfers = BankData::all();
        $typeMobilOptions = JenisMobil::all();

        // Prepare data to pass to the view
        $data = [
            'barangKeluar' => $barangKeluar,
            'warehouses' => $warehouses,
            'customers' => $customers,
            'bankTransfers' => $bankTransfers,
            'typeMobilOptions' => $typeMobilOptions
        ];

        // Load the view and pass the data
        $pdf = Pdf::loadView('data-gudang.barang-keluar.pdfSuratJalan', $data);

        // Download the PDF file
        return $pdf->download('SuratJalan_'.$barangKeluar->nomer_surat_jalan.'.pdf');
    }
}
