<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat Jalan</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            color: #333;
            background-color: #f8f9fa; /* Light background for aesthetics */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100px;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            font-size: 18px;
        }

        .date-owner {
            text-align: right;
            margin: 10px 0;
        }

        .content {
            margin: 0 10%;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff; /* White background for table */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for table */
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
            background-color: #007bff; /* Blue header */
            color: #fff;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            background-color: #f1f1f1; /* Light grey footer */
            padding: 20px; /* Padding for footer */
        }

        .signature {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .signature p {
            margin: 5px 0;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .btn-danger {
                display: none; /* Hide buttons on print */
            }
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
                        <div class="col-sm-12">
                            <div class="header">
                                <img src="{{ asset('ats/ATSLogo.png') }}" alt="Logo">
                                <h1>Surat Jalan</h1>
                                <p>{{ $barangKeluar->nomer_surat_jalan }}</p>
                            </div>
                            <div class="date-owner">
                                <p><strong>Tanggal:</strong> {{ date('d-m-Y') }}</p>
                                <p><strong>Pemilik:</strong> 
                                    @foreach ($customers as $customer)
                                        @if ($customer->id == $barangKeluar->customer_id)
                                            {{ $customer->name }}
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
            <div class="form-group text-center">
                
                <a href="{{ route('surat-jalan.download', $barangKeluar->id) }}" class="btn btn-success">Download PDF</a>
            </div>

                <div class="form-group">
                    <label for="gudang_id">Gudang ATS</label>
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

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Kami Kirimkan barang-barang tersebut di bawah ini dengan kendaraan 
                            <strong>{{ old('type_mobil_id', $barangKeluar->type_mobil_id) ? $typeMobilOptions->firstWhere('id', old('type_mobil_id', $barangKeluar->type_mobil_id))->type : 'Pilih Tipe Mobil' }}</strong>
                            NO : <strong>{{ old('nomer_polisi', $barangKeluar->nomer_polisi) }} {{ old('nomer_container', $barangKeluar->nomer_container) }}</strong>
                        </h3>
                    </div>
                </div>
                <!-- Items Table -->
                <div class="table-responsive">
                    <table class="table" id="items-table">
                        <thead>
                            <tr>
                                <th>Nomer Ref</th>
                                <th>Nama Barang</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangKeluar->items as $item)
                                <tr>
                                    <td>{{ $item->no_ref }}</td>
                                    <td>{{ $item->barang->nama_barang }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group text-center">
                    <a href="{{ route('data-gudang.barang-keluar.index') }}" class="btn btn-danger">Kembali</a>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="footer">
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
                <p>&copy; {{ date('Y') }} ATS Digital. All rights reserved.</p>
            </div>
            <!-- /.footer -->
        </div>
        <!-- /.content-wrapper -->
    </div>
    <!-- ./wrapper -->

    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
