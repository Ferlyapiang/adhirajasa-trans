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

        .label {
            font-weight: bold;
            flex: 0 0 150px;
            margin-right: 10px;
        }

        .colon {
            margin-right: 10px;
        }

        .value {
            flex: 1;
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
                <div class="card">
                    <div class="container-fluid" style="margin-top: 20px;">
                        <div class="row">
                            <div class="col-lg-12">
                                <img src="{{ asset('ats/ATSLogo.png') }}" alt="ATS Logo" style="height: 80px;">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="card-header">
                                                <h4 style="font-size: 1.2em; margin-bottom: 5px;">Kantor Pusat:</h4>
                                            </div>

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
                                        <div class="col-auto">
                                            <div class="branch-offices">
                                                @if ($branchOffices->isEmpty())
                                                <p style="color: #777; font-size: 0.85em;">Tidak ada kantor cabang
                                                    tersedia.
                                                </p>
                                                @else
                                                @foreach ($branchOffices as $branchOffice)
                                                <div>
                                                    <h5 style="font-size: 0.95em; margin: 5px 0;">Kantor Cabang
                                                        {{ $loop->iteration }}:
                                                    </h5>
                                                    <p style="font-size: 0.85em; margin: 2px 0;">Alamat: <span
                                                            class="text-primary">{{ $branchOffice->address ?? 'Alamat tidak tersedia' }}</span>
                                                    </p>
                                                    <p style="font-size: 0.85em; margin: 2px 0;">Nomor Telepon:
                                                        <span
                                                            class="text-primary">{{ $branchOffice->phone_number ?? 'Nomor telepon tidak tersedia' }}</span>
                                                    </p>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h1>Invoice</h1>
                                        <button class="btn btn-primary" onclick="toggleEdit()">Edit</button>
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
                                                    <td id="invoiceNo">{{ $invoiceMaster[0]->nomer_invoice }}</td>
                                                    <td id="tanggalMasuk">{{ $invoiceMaster[0]->tanggal_masuk ?? 'Tanggal transaksi tidak tersedia' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <!-- Edit Form (hidden by default) -->
                                        <form id="editForm" action="{{ route('data-invoice.invoice-reporting.updateInvoice') }}" method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" name="nomer_invoice" value="{{ $invoiceMaster[0]->nomer_invoice }}">
                                            <div class="form-group">
                                                <label for="new_nomer_invoice">New Invoice No</label>
                                                <input type="text" name="new_nomer_invoice" id="new_nomer_invoice" class="form-control" value="{{ $invoiceMaster[0]->nomer_invoice }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="new_tanggal_masuk">New Tanggal</label>
                                                <input type="date" name="new_tanggal_masuk" id="new_tanggal_masuk" class="form-control" value="{{ $invoiceMaster[0]->tanggal_masuk }}" required>
                                            </div>
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row mb-4">
                                    <div class="col-lg-12">
                                        <div class="card-header">

                                            <h1>BILL TO</h1>
                                        </div>
                                        <div class="card-body header-info">
                                            {{ $invoiceMaster[0]->customer_name ?? 'Nama pelanggan tidak tersedia' }}
                                            <br>
                                            Telp:
                                            {{ $invoiceMaster[0]->customer_no_hp ?? 'Nomor telepon pelanggan tidak tersedia' }}
                                            <br>
                                            Alamat:
                                            <td>{{ $invoiceMaster[0]->customer_address ?? 'Alamat pelanggan tidak tersedia' }}
                                            </td>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
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
                                                        <a href="{{ $item->joc_number ? route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) : route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                                                            {{ $item->joc_number ?: $item->nomer_surat_jalan }}
                                                        </a>
                                                        @else
                                                        <a href="{{ $item->joc_number ? route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) : route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                                                            {{ $item->joc_number ?: $item->nomer_surat_jalan }}
                                                        </a>
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
                                                        @if($item->barang_masuks_id && $item->harga_lembur)
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
                                                $words=$units[$number];
                                                } elseif ($number < 20) {
                                                $words=$units[$number - 10] . ' Belas' ;
                                                } elseif ($number < 100) {
                                                $words=$units[intval($number / 10)] . ' Puluh ' .
                                                $units[$number % 10];
                                                } elseif ($number < 1000) {
                                                $words=$units[intval($number / 100)] . ' Ratus ' .
                                                convertToWords($number % 100);
                                                } elseif ($number < 1000000) {
                                                $words=convertToWords(intval($number / 1000)) . ' Ribu ' .
                                                convertToWords($number % 1000);
                                                } elseif ($number < 1000000000) {
                                                $words=convertToWords(intval($number / 1000000)) . ' Juta ' .
                                                convertToWords($number % 1000000);
                                                }

                                                return trim($words);
                                                }

                                                // Wrap the function call to add "Rupiah" only once
                                                function convertToWordsWithCurrency($number)
                                                {
                                                return convertToWords($number) . ' Rupiah' ;
                                                }

                                                @endphp

                                                <tfoot>
                                                @php
                                                $ppn = 0.11 * $subtotal; // 11% PPN
                                                $pph = 0.02 * $subtotal; // 2% PPH
                                                $total = $subtotal + $ppn - $pph - ($totalDiscount ?? 0); // Total after discount, PPN, and PPH
                                                @endphp

                                                <form action="{{ route('data-invoice.invoice-reporting.addDiscountAndNote') }}" method="POST">
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


                                                </form>

                                                @if (!$totalDiscount && !$reportNoted)
                                                <tr>
                                                    <td colspan="5" style="text-align: right;">
                                                        <button type="button" class="btn btn-secondary" id="addDiscountAndNoteButton">Tambah Diskon dan Noted</button>
                                                    </td>
                                                </tr>
                                                @endif

                                                <!-- Delete Button if Discount or Noted exists -->
                                                @if ($totalDiscount || $reportNoted)
                                                <tr>
                                                    <td colspan="5" style="text-align: right;">
                                                        <!-- The delete form should use the correct invoice id -->
                                                        <form action="{{ route('data-invoice.invoice-reporting.deleteDiscount', ['id' => $invoiceSummary->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the discount and note?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus Diskon dan Noted</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endif

                                                <!-- Hidden Form for Adding Discount and Noted -->
                                                <tr id="addDiscountAndNoteForm" style="display: none;">
                                                    <form action="{{ route('data-invoice.invoice-reporting.addDiscountAndNote') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="nomer_invoice" value="{{ $invoiceMaster[0]->nomer_invoice }}">

                                                        <!-- Input for Diskon -->
                                                        <td colspan="1" style="text-align: right;">Diskon</td>
                                                        <td colspan="1"><input type="number" name="diskon" class="form-control" value="0"></td>

                                                        <!-- Input for Noted -->
                                                        <td colspan="1" style="text-align: right;">Noted</td>
                                                        <td colspan="1"><textarea type="text" name="noted" class="form-control" value=""></textarea></td>

                                                        <!-- Submit Button for Discount and Note -->
                                                        <td colspan="2" style="text-align: right;">
                                                            <button type="submit" class="btn btn-primary">Simpan Diskon dan Noted</button>
                                                        </td>
                                                    </form>
                                                </tr>

                                            </tfoot>
                                        </table>
                                        <div class="container">
                                            <div class="row">
                                                <!-- Left Column: Wire Transfer Information -->
                                                <div class="col-md-4">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <strong class="header">W I R E T R A N S F E R</strong>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row" style="margin-top: 10px;">
                                                                <span class="label">Bank Transfer</span>
                                                                <span class="colon">:</span>
                                                                <span class="value"
                                                                    style="font-weight: bold">{{ $invoiceMaster[0]->bank_name ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="row">
                                                                <span class="label">A/C Number</span>
                                                                <span class="colon">:</span>
                                                                <span class="value"
                                                                    style="font-weight: bold">{{ $invoiceMaster[0]->account_number ?? 'N/A' }}</span>
                                                            </div>
                                                            <div class="row">
                                                                <span class="label">A/C Name</span>
                                                                <span class="colon">:</span>
                                                                <span class="value"
                                                                    style="font-weight: bold">{{ $invoiceMaster[0]->account_name ?? 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Right Column: Footer Signature -->
                                                <div class="col-md-8 d-flex flex-column align-items-end footer">
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
                                            <div class="row mt-3">
                                                <div class="col text-center">
                                                    <p>&copy; {{ date('Y') }} ATS Digital. All rights reserved.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-header">
                                    <a href="{{ route('invoice-report.download', $invoiceMaster[0]->id) }}"
                                        class="btn btn-primary">Download PDF</a>
                                    <a href="{{ route('data-invoice.invoice-reporting.index') }}"
                                        class="btn btn-secondary">Back</a>
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
<script>
    document.getElementById('addDiscountAndNoteButton').addEventListener('click', function() {
        document.getElementById('addDiscountAndNoteForm').style.display = 'table-row';
        this.style.display = 'none'; // Hide the "Tambah Diskon dan Noted" button
    });

    function toggleEdit() {
        const editForm = document.getElementById('editForm');
        editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
    }
</script>

</html>