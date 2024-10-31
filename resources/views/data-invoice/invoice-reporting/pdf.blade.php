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
            <h4 style="font-size: 1.2em; margin-bottom: 5px;">Kantor Pusat:</h4>
            <p style="font-size: 0.85em; margin: 2px 0;">Alamat: <br> <span
                    class="text-primary">{{ $headOffice->address ?? 'Alamat tidak tersedia' }}</span>
            </p>
            <p style="font-size: 0.85em; margin: 2px 0;">Nomor Telepon: <br> <span
                    class="text-primary">{{ $headOffice->phone_number ?? 'Nomor telepon tidak tersedia' }}</span>
            </p>
            <p style="font-size: 0.85em; margin: 2px 0;">Email: <br> <span
                    class="text-primary">{{ $headOffice->email ?? 'Email tidak tersedia' }}</span>
            </p>
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
                    <td>{{ $invoiceMaster[0]->tanggal_masuk ?? 'Tanggal transaksi tidak tersedia' }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row mb-4">
            <h1>BILL TO</h1>
            <div class="header-info">
                {{ $invoiceMaster[0]->customer_name ?? 'Nama pelanggan tidak tersedia' }}
                <br>
                Telp:
                {{ $invoiceMaster[0]->customer_no_hp ?? 'Nomor telepon pelanggan tidak tersedia' }}
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
                @php
                    $subtotal = 0;
                @endphp
                @foreach ($invoiceMaster as $item)
                    <tr>
                        <td>
                            @if ($item->harga_lembur)
                                X
                            @elseif ($item->harga_kirim_barang)
                                {{ $item->joc_number ?: $item->nomer_surat_jalan }}
                            @else
                                {{ $item->joc_number ?: $item->nomer_surat_jalan }}
                            @endif
                        </td>
                        <td>
                            @if ($item->harga_lembur)
                                X
                            @elseif ($item->harga_kirim_barang)
                                X
                            @else
                                {{ $item->nomer_polisi ?: $item->nomer_container ?: 'X' }}
                            @endif
                        </td>
                        <td>
                            @if ($item->harga_lembur)
                                X
                            @elseif ($item->harga_kirim_barang)
                                1X Engkel
                            @else
                                {{ $item->total_sisa ?? 'X' }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if ($item->harga_lembur)
                                CASH LEMBUR BONGKAR
                                {{ $item->joc_number ?? $item->nomer_surat_jalan }}
                            @elseif ($item->harga_kirim_barang)
                                Sewa Mobil
                                <strong>{{ $item->warehouse_name ?? 'X' }}<br></strong>
                                {{ $item->address ?? 'X' }}<br>
                                Nomer Container:
                                <strong>{{ $item->nomer_container ?? ($item->nomer_polisi ?? 'X') }}</strong>
                            @else
                                Kontainer
                                <strong>{{ $item->type_mobil ?? '' }}</strong><br>
                                Masa Penimbunan:
                                <strong>{{ $item->tanggal_masuk_penimbunan ? \Carbon\Carbon::parse($item->tanggal_masuk_penimbunan)->format('d/m/Y') : '' }}</strong>
                                -
                                <strong>{{ $item->tanggal_keluar_penimbunan ? \Carbon\Carbon::parse($item->tanggal_keluar_penimbunan)->format('d/m/Y') : '' }}</strong>
                            @endif
                        </td>
                        <td>
                            @php
                                $unitPrice =
                                    $item->harga_lembur ??
                                    ($item->harga_kirim_barang ??
                                        ($item->harga_simpan_barang ?? 0));
                                $subtotal += $unitPrice;
                            @endphp
                            {{ number_format($unitPrice) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            @php
                function convertToWords($number)
                {
                    $units = [
                        '',
                        'Satu',
                        'Dua',
                        'Tiga',
                        'Empat',
                        'Lima',
                        'Enam',
                        'Tujuh',
                        'Delapan',
                        'Sembilan',
                    ];
                    $words = '';

                    if ($number < 10) {
                        $words = $units[$number];
                    } elseif ($number < 20) {
                        $words = $units[$number - 10] . ' Belas';
                    } elseif ($number < 100) {
                        $words =
                            $units[intval($number / 10)] .
                            ' Puluh ' .
                            $units[$number % 10];
                    } elseif ($number < 1000) {
                        $words =
                            $units[intval($number / 100)] .
                            ' Ratus ' .
                            convertToWords($number % 100);
                    } elseif ($number < 1000000) {
                        $words =
                            convertToWords(intval($number / 1000)) .
                            ' Ribu ' .
                            convertToWords($number % 1000);
                    } elseif ($number < 1000000000) {
                        $words =
                            convertToWords(intval($number / 1000000)) .
                            ' Juta ' .
                            convertToWords($number % 1000000);
                    }

                    return trim($words);
                }

                // Wrap the function call to add "Rupiah" only once
                function convertToWordsWithCurrency($number)
                {
                    return convertToWords($number) . ' Rupiah';
                }

            @endphp

            <tfoot>
                @php
                    $ppn = 0.011 * $subtotal; // 11% PPN
                    $pph = 0.02 * $subtotal; // 2% PPH
                    $total = $subtotal + $ppn - $pph;
                @endphp

                @if (!empty($invoiceMaster[0]->customer_no_npwp))
                    <tr>
                        <td colspan="4" style="text-align: right;">Subtotal</td>
                        <td>{{ number_format($subtotal) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;">PPN (1.1%)</td>
                        <td>{{ number_format($ppn) }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;">PPH (2%)</td>
                        <td> - {{ number_format($pph) }} </td>
                    </tr>
                    <tr>
                        <td colspan="4"
                            style="text-align: right; font-weight: bold;">Total</td>
                        <td style="font-weight: bold;">{{ number_format($total) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"
                            style="text-align: right; font-style: italic;">Total:
                            {{ convertToWordsWithCurrency($total) }}</td>
                    </tr>
                @elseif (!empty($invoiceMaster[0]->customer_no_ktp))
                    <tr>
                        <td colspan="4"
                            style="text-align: right; font-weight: bold;">Total</td>
                        <td style="font-weight: bold;">{{ number_format($subtotal) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5"
                            style="text-align: right; font-style: italic;">Total:
                            {{ convertToWordsWithCurrency($subtotal) }}</td>
                    </tr>
                @endif
            </tfoot>

        </table>
        <div class="wire-transfer">
            <strong class="header">W I R E T R A N S F E R</strong>

            <div class="row" style="margin-top: 10px;">
                <span class="label">Bank Transfer</span>
                <span class="colon">:</span>
                <span class="value" style="font-weight: bold">{{ $invoiceMaster[0]->bank_name ?? 'N/A' }}</span>
            </div>

            <div class="row">
                <span class="label">A/C Number</span>
                <span class="colon">:</span>
                <span class="value" style="font-weight: bold">{{ $invoiceMaster[0]->account_number ?? 'N/A' }}</span>
            </div>

            <div class="row">
                <span class="label">A/C Name</span>
                <span class="colon">:</span>
                <span class="value" style="font-weight: bold">{{ $invoiceMaster[0]->account_name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</body>

</html>
