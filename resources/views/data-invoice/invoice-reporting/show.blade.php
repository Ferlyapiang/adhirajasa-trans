<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Invoice</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .header-info {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        h1,
        h4 {
            color: #2c3e50;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .table tfoot td {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .text-primary {
            color: #007bff;
        }

        .invoice-header {
            margin-bottom: 20px;
            padding: 10px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <x-sidebar />
        <!-- /.sidebar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Invoice</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <img src="{{ asset('ats/ATSLogo.png') }}" alt="ATS Logo" style="height: 80px;">
                            <div class="row">
                                <!-- Kantor Pusat Section -->
                                <div class="col-lg-2">
                                    <div class="invoice-header text-left">
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
                                </div>

                                <!-- Kantor Cabang Section -->
                                <div class="col-lg-4">
                                    <div class="branch-offices">
                                        @if ($branchOffices->isEmpty())
                                            <p style="color: #777; font-size: 0.85em;">Tidak ada kantor cabang tersedia.
                                            </p>
                                        @else
                                            @foreach ($branchOffices as $branchOffice)
                                                <div>
                                                    <h5 style="font-size: 0.95em; margin: 5px 0;">Kantor Cabang
                                                        {{ $loop->iteration }}:</h5>
                                                    <p style="font-size: 0.85em; margin: 2px 0;">Alamat: <span
                                                            class="text-primary">{{ $branchOffice->address ?? 'Alamat tidak tersedia' }}</span>
                                                    </p>
                                                    <p style="font-size: 0.85em; margin: 2px 0;">Nomor Telepon: <span
                                                            class="text-primary">{{ $branchOffice->phone_number ?? 'Nomor telepon tidak tersedia' }}</span>
                                                    </p>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Header and Details -->

                            <!-- Invoice Details Table -->
                            <div class="card">
                                <div class="card-header">
                                    <h1>Invoice</h1>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
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
                                </div>
                            </div>

                            <!-- Billing Information -->
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <h1>BILL TO</h1>
                                    <div class="header-info">
                                        {{ $invoiceMaster[0]->customer_name ?? 'Nama pelanggan tidak tersedia' }}
                                        <br>
                                        Telp:
                                        {{ $invoiceMaster[0]->customer_no_hp ?? 'Nomor telepon pelanggan tidak tersedia' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Barang Masuk Table -->
                            <div class="card">
                                <div class="card-header">
                                    <h1>Detail Barang</h1>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
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
                                                                CASH LEMBUR BONGKAR {{ $item->joc_number ?? $item->nomer_surat_jalan }}
                                                            @elseif ($item->harga_kirim_barang)
                                                                Sewa Mobil <strong>{{ $item->warehouse_name ?? 'X' }}<br></strong> 
                                                                {{ $item->address ?? 'X' }}<br>
                                                                Nomer Container: <strong>{{ $item->nomer_container ?? $item->nomer_polisi ?? 'X' }}</strong> 
                                                            @else
                                                                Kontainer <strong>{{ $item->type_mobil ?? '' }}</strong><br>
                                                                Masa Penimbunan:
                                                                <strong>{{ $item->tanggal_masuk_penimbunan ? \Carbon\Carbon::parse($item->tanggal_masuk_penimbunan)->format('d/m/Y') : '' }}</strong>
                                                                -
                                                                <strong>{{ $item->tanggal_keluar_penimbunan ? \Carbon\Carbon::parse($item->tanggal_keluar_penimbunan)->format('d/m/Y') : '' }}</strong>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php
                                                                $unitPrice = $item->harga_lembur ?? $item->harga_kirim_barang ?? $item->harga_simpan_barang ?? 0;
                                                                $subtotal += $unitPrice;
                                                            @endphp
                                                            {{ number_format($unitPrice) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            @php
function convertToWords($number) {
    $units = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan'];
    $words = '';

    if ($number < 10) {
        $words = $units[$number];
    } elseif ($number < 20) {
        $words = $units[$number - 10] . ' Belas';
    } elseif ($number < 100) {
        $words = $units[intval($number / 10)] . ' Puluh ' . $units[$number % 10];
    } elseif ($number < 1000) {
        $words = $units[intval($number / 100)] . ' Ratus ' . convertToWords($number % 100);
    } elseif ($number < 1000000) {
        $words = convertToWords(intval($number / 1000)) . ' Ribu ' . convertToWords($number % 1000);
    } elseif ($number < 1000000000) {
        $words = convertToWords(intval($number / 1000000)) . ' Juta ' . convertToWords($number % 1000000);
    }

    return trim($words);
}

// Wrap the function call to add "Rupiah" only once
function convertToWordsWithCurrency($number) {
    return convertToWords($number) . ' Rupiah';
}


@endphp

<tfoot>
    @php
        $ppn = 0.011 * $subtotal;  // 11% PPN
        $pph = 0.02 * $subtotal;   // 2% PPH
        $total = $subtotal + $ppn + $pph;
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
            <td>{{ number_format($pph) }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
            <td style="font-weight: bold;">{{ number_format($total) }}</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right; font-style: italic;">Total: {{ convertToWordsWithCurrency($total) }}</td>
        </tr>
    @elseif (!empty($invoiceMaster[0]->customer_no_ktp))
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Total</td>
            <td style="font-weight: bold;">{{ number_format($subtotal) }}</td>
        </tr>
        <tr>
            <td colspan="5" style="text-align: right; font-style: italic;">Total: {{ convertToWordsWithCurrency($subtotal) }}</td>
        </tr>
    @endif
</tfoot>

                                            
                                        </table>
                                        
                                        
                                        <a href="{{ route('invoice-report.download', $invoiceMaster[0]->id) }}" class="btn btn-primary">Download PDF</a>
                                        <a href="{{ route('data-invoice.invoice-reporting.display') }}" class="btn btn-secondary">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include('admin.footer')
        <!-- /.footer -->
    </div>
    <!-- ./wrapper -->

    <!-- AdminLTE App -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
