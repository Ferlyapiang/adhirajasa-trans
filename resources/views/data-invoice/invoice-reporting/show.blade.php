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
                                                <td>{{ $invoiceMaster[0]->tanggal_masuk ?: ($invoiceMaster[0]->tanggal_keluar ?: 'Tanggal transaksi tidak tersedia') }}
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
                                        {{ $invoiceMaster[0]->customer_masuk_name ?? ($invoiceMaster[0]->customer_keluar_name ?? 'Nama pelanggan tidak tersedia') }}
                                        <br>
                                        Telp:
                                        {{ $invoiceMaster[0]->customer_masuk_no_hp ?? $invoiceMaster[0]->customer_keluar_no_hp }}
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
                                                @foreach ($invoiceMaster as $item)
                                                    <tr>
                                                        <td>{{ $item->joc_number ?: $item->nomer_surat_jalan }}</td>
                                                        <td>{{ $item->nomer_polisi_masuk ?: $item->nomer_polisi_keluar ?: $item->nomer_container_masuk ?: $item->nomer_container_keluar ?: '' }}
                                                        </td>
                                                        <td>{{ $item->total_qty_masuk ?: $item->total_qty_keluar_keluar ?: '' }}
                                                        </td>
                                                        <td style="text-align: center;">
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
                                                        return $carry +
                                                            ($item->total_harga_simpan_lembur ?:
                                                            $item->total_harga_barang_keluar ?:
                                                                0);
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
                                                        <td colspan="4" class="text-right"><strong>PPN
                                                                (1.1%)</strong></td>
                                                        <td>{{ number_format($ppn, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" class="text-right"><strong>PPH (2%)</strong>
                                                        </td>
                                                        <td>{{ number_format($pph, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="4" class="text-right"><strong>Diskon</strong></td>
                                                    <td>{{ number_format($totalDiskon, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-right"><strong>Total Akhir</strong>
                                                    </td>
                                                    <td>{{ number_format($totalAkhir, 0, ',', '.') }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <a href="{{ route('invoice.download', $invoiceMaster[0]->id) }}" class="btn btn-primary">Download PDF</a>
                                        <a href="{{ route('data-invoice.invoice-reporting.index') }}" class="btn btn-secondary">Back</a>
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
