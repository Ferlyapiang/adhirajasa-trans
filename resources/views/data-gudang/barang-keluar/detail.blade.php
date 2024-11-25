<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang Keluar</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
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
                            <h1 class="m-0">Detail Barang Keluar</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h3 class="card-title">Form Barang Keluar</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ route('pdf.invoice-barang-keluar', ['id' => $barangKeluar->id]) }}" class="btn btn-secondary float-right mr-2">Tanpa Pajak PDF</a>
                                            <a href="{{ route('pdf.invoice-barang-keluar-pajak', ['id' => $barangKeluar->id]) }}" class="btn btn-success float-right mr-2">Download Pajak PDF</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('data-gudang.barang-keluar.update', $barangKeluar->id) }}"
                                        method="POST" id="barangKeluarForm">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="nomer_invoice">Nomor Ref</label>
                                            <input type="text" name="nomer_invoice" id="nomer_invoice"
                                                class="form-control @error('nomer_invoice') is-invalid @enderror"
                                                value="{{ old('nomer_invoice', $barangKeluar->nomer_invoice) }}"
                                                readonly>
                                            @error('nomer_invoice')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nomer_surat_jalan">Nomer Surat Jalan</label>
                                            <input type="text" name="nomer_surat_jalan" id="nomer_surat_jalan"
                                                class="form-control @error('nomer_surat_jalan') is-invalid @enderror"
                                                value="{{ old('nomer_surat_jalan', $barangKeluar->nomer_surat_jalan) }}"
                                                readonly>
                                            @error('nomer_surat_jalan')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tanggal_keluar">Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                                class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                                value="{{ old('tanggal_keluar', $barangKeluar->tanggal_keluar) }}"
                                                readonly>
                                            @error('tanggal_keluar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="gudang_id">Gudang</label>
                                            <select name="gudang_id" id="gudang_id"
                                                class="form-control @error('gudang_id') is-invalid @enderror" disabled>
                                                <option value="">Select Gudang</option>
                                                @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('gudang_id', $barangKeluar->gudang_id) == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('gudang_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_id">Customer</label>
                                            <select name="customer_id" id="customer_id"
                                                class="form-control @error('customer_id') is-invalid @enderror" disabled>
                                                <option value="">Select Customer</option>
                                                @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id', $barangKeluar->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_selection" class="form-label">Choose Identification Type:</label>
                                            <select id="id_selection" class="form-control" onchange="toggleFields()" disabled required>
                                                <option value="">-- Select --</option>
                                                <option value="nomer_polisi" {{ $barangKeluar->nomer_polisi ? 'selected' : '' }}>Nomer Polisi</option>
                                                <option value="nomer_container" {{ $barangKeluar->nomer_container ? 'selected' : '' }}>Nomer Container</option>
                                            </select>
                                        </div>

                                        <div id="nomer_polisi_field" class="mb-3" style="display: none;">
                                            <label for="nomer_polisi">Nomer Polisi</label>
                                            <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control" disabled
                                                value="{{ $barangKeluar->nomer_polisi }}">
                                        </div>

                                        <div id="nomer_container_field" class="mb-3" style="display: none;">
                                            <label for="nomer_container">Nomer Container</label>
                                            <input type="text" name="nomer_container" id="nomer_container" class="form-control" disabled
                                                value="{{ $barangKeluar->nomer_container }}">
                                        </div>

                                        <div class="form-group" hidden>
                                            <label for="bank_transfer_id">Bank Transfer</label>
                                            <select name="bank_transfer_id" id="bank_transfer_id"
                                                class="form-control @error('bank_transfer_id') is-invalid @enderror"
                                                disabled>
                                                <option value="">-- None --</option>
                                                @foreach ($bankTransfers as $bankTransfer)
                                                <option value="{{ $bankTransfer->id }}"
                                                    {{ old('bank_transfer_id', $barangKeluar->bank_transfer_id) == $bankTransfer->id ? 'selected' : '' }}>
                                                    {{ $bankTransfer->bank_name }} -
                                                    {{ $bankTransfer->account_number }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('bank_transfer_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <h2>Items</h2>

                                        <input type="hidden" name="items" id="items-input" value="[]">

                                        <!-- Items Table -->
                                        <div class="table-responsive">
                                            <table class="table" id="items-table">
                                                <thead>
                                                    <tr>
                                                        <th>Nomer Ref</th>
                                                        <th>Nama Barang</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Harga</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $total_before_tax = 0; // Inisialisasi variabel untuk menyimpan total harga sebelum pajak
                                                    @endphp
                                                    @foreach ($barangKeluar->items as $item)
                                                    @php
                                                    $total_before_tax += $item->total_harga; // Menambahkan total harga item ke total sebelum pajak
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $item->no_ref }}</td>
                                                        <td>{{ $item->barang->nama_barang }}</td>
                                                        <td>{{ $item->qty }}</td>
                                                        <td>{{ $item->unit }}</td>
                                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                        <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    @php
                                                    $ppn_rate = 0.011; // Tarif PPN 1.1%
                                                    $pph_rate = 0.02; // Tarif PPH 23 (2%)

                                                    $ppn = $total_before_tax * $ppn_rate;
                                                    $pph = $total_before_tax * $pph_rate;
                                                    $total_after_tax = $total_before_tax + $ppn + $pph;

                                                    // Include NumberToWords helper
                                                    use App\Helpers\NumberToWords;

                                                    $total_before_tax_words = NumberToWords::convert($total_before_tax);
                                                    $ppn_words = NumberToWords::convert($ppn);
                                                    $pph_words = NumberToWords::convert($pph);
                                                    $total_after_tax_words = NumberToWords::convert($total_after_tax);
                                                    @endphp
                                                    <tr style="background-color: #e9ecef; border-top: 2px solid #ddd;">
                                                        <td colspan="5" style="text-align: right; font-weight: bold; padding: 10px;">Total Harga Sebelum Pajak:</td>
                                                        <td style="font-weight: bold; padding: 10px;">Rp. {{ number_format($total_before_tax, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr style="background-color: #f2f2f2;">
                                                        <td colspan="5" style="text-align: right; font-weight: bold; padding: 10px;">PPN 1.1%:</td>
                                                        <td style="font-weight: bold; padding: 10px;">Rp. {{ number_format($ppn, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr style="background-color: #f9f9f9;">
                                                        <td colspan="5" style="text-align: right; font-weight: bold; padding: 10px;">PPH 23 (2%):</td>
                                                        <td style="font-weight: bold; padding: 10px;">Rp. {{ number_format($pph, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr style="background-color: #94ca19; color: white;">
                                                        <td colspan="5" style="text-align: right; font-weight: bold; padding: 10px;">Total Invoice Setelah Kena Pajak:</td>
                                                        <td style="font-weight: bold; padding: 10px;">Rp. {{ number_format($total_after_tax, 0, ',', '.') }}</td>
                                                    </tr>
                                                    <tr style="background-color: #f2f2f2;">
                                                        <td colspan="6" style="text-align: right; font-weight: bold; padding: 10px;">
                                                            Terbilang: {{ $total_after_tax_words }}
                                                        </td>
                                                    </tr>
                                                </tfoot>


                                            </table>
                                        </div>

                                        <br><br>
                                        <div class="card-footer">
                                            <a href="{{ route('data-gudang.barang-keluar.index') }}"
                                                class="btn btn-secondary">Back</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Footer -->

            <!-- /.footer -->
        </div>
        @include('admin.footer')
        <!-- ./wrapper -->
</body>
<script>
     document.addEventListener("DOMContentLoaded", function() {
                var nomerPolisi = "{{ $barangKeluar->nomer_polisi }}";
                var nomerContainer = "{{ $barangKeluar->nomer_container }}";
                var idSelection = document.getElementById('id_selection');

                if (nomerPolisi) {
                    idSelection.value = 'nomer_polisi';
                    document.getElementById('nomer_polisi_field').style.display = 'block';
                    document.getElementById('nomer_container_field').style.display = 'none'; // Sembunyikan field lainnya
                } else if (nomerContainer) {
                    idSelection.value = 'nomer_container';
                    document.getElementById('nomer_container_field').style.display = 'block';
                    document.getElementById('nomer_polisi_field').style.display = 'none'; // Sembunyikan field lainnya
                } else {
                    idSelection.value = '';
                }
            });

            function toggleFields() {
                var selection = document.getElementById('id_selection').value;

                document.getElementById('nomer_polisi_field').style.display = 'none';
                document.getElementById('nomer_container_field').style.display = 'none';

                document.getElementById('nomer_polisi').value = '';
                document.getElementById('nomer_container').value = '';

                if (selection === 'nomer_polisi') {
                    document.getElementById('nomer_polisi_field').style.display = 'block';
                } else if (selection === 'nomer_container') {
                    document.getElementById('nomer_container_field').style.display = 'block';
                }
            }
</script>

</html>