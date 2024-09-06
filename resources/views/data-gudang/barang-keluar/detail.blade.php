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
        @include('admin.sidebar')
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
                                    <h3 class="card-title">Form Barang Keluar</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('data-gudang.barang-keluar.update', $barangKeluar->id) }}"
                                        method="POST" id="barangKeluarForm">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group">
                                            <label for="nomer_invoice">Nomor Container</label>
                                            <input type="text" name="nomer_invoice" id="nomer_invoice"
                                                class="form-control @error('nomer_invoice') is-invalid @enderror"
                                                value="{{ old('nomer_invoice', $barangKeluar->nomer_invoice) }}"
                                                readonly>
                                            @error('nomer_invoice')
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

                                        <div class="form-group">
                                            <label for="nomer_polisi">Nomor Polisi</label>
                                            <input type="text" name="nomer_polisi" id="nomer_polisi"
                                                class="form-control @error('nomer_polisi') is-invalid @enderror"
                                                value="{{ old('nomer_polisi', $barangKeluar->nomer_polisi) }}"
                                                readonly>
                                            @error('nomer_polisi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
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
                                                    @foreach ($barangKeluar->items as $item)
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

</html>
