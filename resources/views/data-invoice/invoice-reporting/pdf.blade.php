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

        /* General container styling */
.container {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
}

/* Row layout with left and right columns */
.row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

/* Left column for wire transfer details */
.left-column {
    width: 40%;
}

.card {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 15px;
}

.card-header {
    font-weight: bold;
    text-align: center;
    margin-bottom: 10px;
}

.card-body .row {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
}

.label, .colon, .value {
    font-weight: bold;
}

/* Right column for signatures */
.right-column {
    width: 55%;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.signature {
    display: flex;
    gap: 50px;
    margin-top: 20px;
}

.left-signature, .right-signature {
    text-align: center;
}

/* Footer text styling */
.footer-text {
    text-align: center;
    margin-top: 30px;
    font-size: 14px;
    color: #666;
}


    </style>
</head>

<body>
    <div class="content">
        <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo" style="height: 80px;"> <br>

        <div style="margin-bottom: 20px;">
            <div style="display: inline-block; width: 28%; vertical-align: top; margin-right: 2%;">
                <h4 style="font-size: 1.2em; margin-bottom: 5px;">Kantor Pusat:</h4>
                <p style="font-size: 0.85em; margin: 2px 0;">Alamat: <br> <span class="text-primary">{{ $headOffice->address ?? 'Alamat tidak tersedia' }}</span></p>
                <p style="font-size: 0.85em; margin: 2px 0;">Nomor Telepon: <br> <span class="text-primary">{{ $headOffice->phone_number ?? 'Nomor telepon tidak tersedia' }}</span></p>
                <p style="font-size: 0.85em; margin: 2px 0;">Email: <br> <span class="text-primary">{{ $headOffice->email ?? 'Email tidak tersedia' }}</span></p>
            </div>
            <div style="display: inline-block; width: 48%; vertical-align: top;">
                <h4 style="font-size: 1.2em; margin-bottom: 5px;">Kantor Cabang:</h4>
                @if ($branchOffices->isEmpty())
                    <p style="color: #777; font-size: 0.85em;">Tidak ada kantor cabang tersedia.</p>
                @else
                    @foreach ($branchOffices as $branchOffice)
                        <div>
                            <h5 style="font-size: 0.95em; margin: 5px 0;">Kantor Cabang {{ $loop->iteration }}:</h5>
                            <p style="font-size: 0.85em; margin: 2px 0;">Alamat: <span class="text-primary">{{ $branchOffice->address ?? 'Alamat tidak tersedia' }}</span></p>
                            <p style="font-size: 0.85em; margin: 2px 0;">Nomor Telepon: <span class="text-primary">{{ $branchOffice->phone_number ?? 'Nomor telepon tidak tersedia' }}</span></p>
                        </div>
                    @endforeach
                @endif
            </div>
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
                <br>
                Alamat:
                {{ $invoiceMaster[0]->customer_address ?? 'Alamat pelanggan tidak tersedia' }}
                
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
                        @if ($item->joc_number)
                        <a href="{{ route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) }}">
                            {{ $item->joc_number }}
                        </a>
                        @elseif (isset($item->barang_keluars_id))
                        <a href="{{ route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                            {{ $item->nomer_surat_jalan }}
                        </a>
                        @else
                        X
                        @endif
                        @else
                        @if ($item->joc_number)
                        <a href="{{ route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) }}">
                            {{ $item->joc_number }}
                        </a>
                        @elseif (isset($item->barang_keluars_id))
                        <a href="{{ route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                            {{ $item->nomer_surat_jalan }}
                        </a>
                        @else
                        X
                        @endif
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
                        @elseif ($item->notedRokok)
                            X
                        @else
                            {{ $item->total_sisa ?? 'X' }}
                        @endif
                    </td>

                    <td style="text-align: center;">
                        @if ($item->harga_lembur)
                            @if ($item->barang_masuks_id && $item->harga_lembur)
                                CAS LEMBUR BONGKAR
                                <a href="{{ $item->joc_number ? route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) : route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                                    {{ $item->joc_number ?? $item->nomer_surat_jalan }}
                                </a>
                            @elseif ($item->barang_keluars_id && $item->harga_lembur)
                                CAS LEMBUR MUAT
                                <a href="{{ $item->joc_number ? route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) : route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                                    {{ $item->joc_number ?? $item->nomer_surat_jalan }}
                                </a>
                            @endif
                        @elseif ($item->harga_kirim_barang)
                            Sewa Mobil
                            <strong>{{ $item->warehouse_name ?? 'X' }}<br></strong>
                            {{ $item->address ?? 'X' }}<br>
                            Nomer Container:
                            <strong>{{ $item->nomer_container ?? ($item->nomer_polisi ?? 'X') }}</strong>
                        @elseif ($item->notedRokok)
                            <strong>{{ $item->notedRokok }}</strong>
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
                        ($item->harga_lembur ??
                        ($item->harga_kirim_barang ??
                        ($item->harga_simpan_barang ?? 0))) +
                        ($item->rokok ?? 0); // Include Rokok if not null
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
        $ppn = 0.11 * $subtotal; // 11% PPN
        $pph = 0.02 * $subtotal; // 2% PPH
        $total = $subtotal + $ppn - $pph - ($totalDiscount ?? 0); // Total after discount, PPN, and PPH
    @endphp

    
        @csrf

        <!-- Hidden Nomer Invoice Field -->
        <input type="hidden" name="nomer_invoice" value="{{ $invoiceMaster[0]->nomer_invoice }}">

        @if (!empty($invoiceMaster[0]->customer_no_npwp))

            <!-- Display Subtotal -->
            <tr>
                <td colspan="4" style="text-align: right;">Sub total :</td>
                <td>{{ number_format($subtotal) }}</td>
            </tr>

            <!-- Display Total Discount if set and not 0 -->
            @if ($invoiceSummary && $invoiceSummary->total_diskon > 0)
                <tr>
                    <td colspan="4" style="text-align: right;">Total Diskon :</td>
                    <td>{{ number_format($invoiceSummary->total_diskon) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Noted</td>
                    <td>{{ $invoiceSummary->concatenated_noted }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">TOTAL SEBELUM PAJAK :</td>
                    <td>{{ number_format($subtotal - $invoiceSummary->total_diskon) }}</td>
                </tr>
            @endif

            <!-- Display PPN and PPH -->
            <tr>
                <td colspan="4" style="text-align: right;">PPN (11%)</td>
                <td>{{ number_format($ppn) }}</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;">PPH (2%)</td>
                <td>( {{ number_format($pph) }} )</td>
            </tr>
            @if ($invoiceSummary && $invoiceSummary->total_diskon > 0)
            <tr>
                <td colspan="4" style="text-align: right;">TOTAL SETELAH PAJAK :</td>
                <td>{{ number_format($subtotal - $invoiceSummary->total_diskon + $ppn - $pph) }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-style: italic;">
                    Total: {{ convertToWordsWithCurrency($subtotal - $invoiceSummary->total_diskon + $ppn - $pph) }}
                </td>
            </tr>
            @else
            <tr>
                <td colspan="4" style="text-align: right;">TOTAL SETELAH PAJAK :</td>
                <td>{{ number_format($subtotal  + $ppn - $pph) }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-style: italic;">
                    Total: {{ convertToWordsWithCurrency($subtotal  + $ppn - $pph) }}
                </td>
            </tr>
            @endif

        @elseif (!empty($invoiceMaster[0]->customer_no_ktp))
            
            @if ($invoiceSummary && $invoiceSummary->total_diskon > 0)
                
                <tr>
                    <td colspan="4" style="text-align: right;">Sub Total</td>
                    <td>{{ number_format($subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Total Diskon :</td>
                    <td>{{ number_format($invoiceSummary->total_diskon) }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right;">Noted</td>
                    <td>{{ $invoiceSummary->concatenated_noted }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
                <td style="font-weight: bold;">{{ number_format($subtotal  - $invoiceSummary->total_diskon) }}</td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right; font-style: italic;">
                    Total: {{ convertToWordsWithCurrency($subtotal - $invoiceSummary->total_diskon) }}
                </td>
            </tr>

        @endif


            </tfoot>

        </table>
        <div class="container">
            <div class="row">
                <!-- Left Column: Wire Transfer Information -->
                <div class="left-column">
                    <div class="card">
                        <div class="card-header">
                            <strong class="header">W I R E T R A N S F E R</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <span class="label">Bank Transfer</span>
                                <span class="colon">:</span>
                                <span class="value">{{ $invoiceMaster[0]->bank_name ?? 'N/A' }}</span>
                            </div>
                            <div class="row">
                                <span class="label">A/C Number</span>
                                <span class="colon">:</span>
                                <span class="value">{{ $invoiceMaster[0]->account_number ?? 'N/A' }}</span>
                            </div>
                            <div class="row">
                                <span class="label">A/C Name</span>
                                <span class="colon">:</span>
                                <span class="value">{{ $invoiceMaster[0]->account_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
        
                <!-- Right Column: Footer Signature -->
                <div class="right-column">
                    <div class="signature">
                        <div class="left-signature">
                            <p>Tanda Terima</p>
                            <p>_______________________</p>
                        </div>
                        <div class="right-signature">
                            <p>Hormat Kami</p>
                            <p>ATS Digital</p>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Footer Text at Bottom -->
            <div class="footer-text">
                <p>&copy; {{ date('Y') }} ATS Digital. All rights reserved.</p>
            </div>
        </div>
        
        
    </div>
        
    </div>
</body>

</html>
