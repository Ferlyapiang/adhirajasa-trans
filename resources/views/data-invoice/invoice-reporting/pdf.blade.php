<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoiceMaster[0]->nomer_invoice }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header-info {
            font-weight: bold;
            font-size: 14px;
            color: #333;
        }

        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin: 20px 0 10px;
        }

        h4 {
            font-size: 18px;
            margin: 5px 0;
        }

        .text-primary {
            color: #007bff;
        }

        .invoice-header {
            margin-bottom: 20px;
            padding: 10px;
        }

        .content {
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="content">
        <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo" style="height: 80px; margin-bottom: 20px;"> <br> <br>

        <div class="invoice-header">
            <h4>Kantor Pusat:</h4>
            <p>Alamat: <span class="text-primary">{{ $headOffice->address ?? 'Alamat tidak tersedia' }}</span></p>
            <p>Nomor Telepon: <span
                    class="text-primary">{{ $headOffice->phone_number ?? 'Nomor telepon tidak tersedia' }}</span></p>
            <p>Email: <span class="text-primary">{{ $headOffice->email ?? 'Email tidak tersedia' }}</span></p>
        </div>

        <h1>Invoice</h1>
        <table>
            <thead>
                <tr>
                    <th>Invoice No</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoiceMaster[0]->nomer_invoice }}</td>
                    <td>{{ $invoiceMaster[0]->tanggal_masuk ?: ($invoiceMaster[0]->tanggal_keluar ?: 'Tanggal transaksi tidak tersedia') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row mb-4">
            <h1>BILL TO</h1>
            <div class="header-info">
                {{ $invoiceMaster[0]->customer_masuk_name ?? ($invoiceMaster[0]->customer_keluar_name ?? 'Nama pelanggan tidak tersedia') }}<br>
                Telp: {{ $invoiceMaster[0]->customer_masuk_no_hp ?? $invoiceMaster[0]->customer_keluar_no_hp }}
            </div>
        </div>

        <h1>Detail Barang</h1>
        <table>
            <thead>
                <tr>
                    <th>Job Order</th>
                    <th>No Kontainer</th>
                    <th>QTY</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoiceMaster as $item)
                    <tr>
                        <td>{{ $item->joc_number ?: $item->nomer_surat_jalan }}</td>
                        <td>{{ $item->nomer_polisi_masuk ?: $item->nomer_polisi_keluar ?: $item->nomer_container_masuk ?: $item->nomer_container_keluar ?: '' }}
                        </td>
                        <td>{{ $item->total_qty_masuk ?: $item->total_qty_keluar_keluar ?: '' }}</td>
                        <td>
                            Kontainer
                            <strong>{{ $item->type_mobil_masuk ?: $item->type_mobil_keluar ?: '' }}</strong><br>
                            Masa Penimbunan:
                            <strong>{{ $item->tanggal_masuk_barang ? \Carbon\Carbon::parse($item->tanggal_masuk_barang)->format('d/m/Y') : ($item->tanggal_keluar ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d/m/Y') : '') }}</strong>
                            -
                            <strong>{{ $item->tanggal_tagihan_masuk ? \Carbon\Carbon::parse($item->tanggal_tagihan_masuk)->format('d/m/Y') : ($item->tanggal_tagihan_keluar ? \Carbon\Carbon::parse($item->tanggal_tagihan_keluar)->format('d/m/Y') : '') }}</strong>
                        </td>
                        <td>{{ number_format($item->total_harga_simpan_lembur ?: $item->total_harga_barang_keluar, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            @php
                // Hitung total harga
                $totalHarga = array_reduce(
                    $invoiceMaster,
                    function ($carry, $item) {
                        return $carry + ($item->total_harga_simpan_lembur ?: $item->total_harga_barang_keluar ?: 0);
                    },
                    0,
                );

                // Hitung total diskon
                $totalDiskon = array_reduce(
                    $invoiceMaster,
                    function ($carry, $item) {
                        return $carry + ($item->diskon ?: 0); // Assumes diskon is a property of each item
                    },
                    0,
                );

                // Inisialisasi PPN dan PPH
                $ppn = 0;
                $pph = 0;

                // Cek apakah $invoiceMaster tidak kosong
                if (count($invoiceMaster) > 0) {
                    $firstItem = $invoiceMaster[0];

                    // Jika ada no_npwp_masuk atau no_npwp_keluar
                    if ($firstItem->no_npwp_masuk || $firstItem->no_npwp_keluar) {
                        $ppn = $totalHarga * 0.011; // 1.1%
                        $pph = $totalHarga * 0.02; // 2%
                    }
                    // Jika hanya ada no_ktp_keluar, tidak ada pajak tambahan
                    elseif ($firstItem->no_ktp_keluar || $firstItem->no_ktp_masuk) {
                        // Tidak ada perhitungan pajak
                        $ppn = 0;
                        $pph = 0;
                    }
                }

                // Total akhir
                $totalAkhir = $totalHarga + $ppn + $pph - $totalDiskon; // Subtract total diskon
            @endphp

            <tfoot>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                    <td>{{ number_format($totalHarga, 0, ',', '.') }}</td>
                </tr>
                @if ($ppn > 0 || $pph > 0)
                    <tr>
                        <td colspan="4" class="text-right"><strong>PPN (1.1%)</strong></td>
                        <td>{{ number_format($ppn, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right"><strong>PPH (2%)</strong></td>
                        <td>{{ number_format($pph, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-right"><strong>Diskon</strong></td>
                    <td>{{ number_format($totalDiskon, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right"><strong>Total Akhir</strong></td>
                    <td>{{ number_format($totalAkhir, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
