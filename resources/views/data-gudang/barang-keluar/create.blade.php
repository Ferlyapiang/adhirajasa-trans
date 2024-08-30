<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Barang Keluar</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Custom CSS -->
    <style>
        .form-group {
            margin-bottom: 1rem;
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
                            <h1 class="m-0">Create Barang Keluar</h1>
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
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Form Barang Keluar</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('data-gudang.barang-keluar.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="tanggal_keluar">Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                                class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                                value="{{ old('tanggal_keluar') }}" required>
                                            @error('tanggal_keluar')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="gudang_id">Gudang</label>
                                            <select name="gudang_id" id="gudang_id"
                                                class="form-control @error('gudang_id') is-invalid @enderror" required>
                                                <option value="">Select Gudang</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}"
                                                        {{ old('gudang_id') == $warehouse->id ? 'selected' : '' }}>
                                                        {{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('gudang_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_id">Pemilik Barang</label>
                                            <select name="customer_id" id="customer_id"
                                                class="form-control @error('customer_id') is-invalid @enderror"
                                                required>
                                                <option value="">Select Pemilik Barang</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nomer_container">Nomer Container</label>
                                            <input type="text" name="nomer_container" id="nomer_container"
                                                class="form-control @error('nomer_container') is-invalid @enderror"
                                                value="{{ old('nomer_container') }}" required>
                                            @error('nomer_container')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nomer_polisi">Nomer Polisi</label>
                                            <input type="text" name="nomer_polisi" id="nomer_polisi"
                                                class="form-control @error('nomer_polisi') is-invalid @enderror"
                                                value="{{ old('nomer_polisi') }}">
                                            @error('nomer_polisi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- <div class="form-group">
                                                    <label for="bank_transfer_id">Bank Transfer</label>
                                                    <select name="bank_transfer_id" id="bank_transfer_id" class="form-control @error('bank_transfer_id') is-invalid @enderror">
                                                        <option value="">Select Bank Transfer</option>
                                                        @foreach ($bankTransfers as $bankTransfer)
                                                            <option value="{{ $bankTransfer->id }}" {{ old('bank_transfer_id') == $bankTransfer->id ? 'selected' : '' }}>{{ $bankTransfer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('bank_transfer_id')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div> --}}
                                        <div class="form-group">
                                            <label for="bank_transfer_id">Bank Transfer</label>
                                            <select name="bank_transfer_id" id="bank_transfer_id"
                                                class="form-control @error('bank_transfer_id') is-invalid @enderror">
                                                <option value="">Select Bank Transfer</option>
                                                @foreach ($bankTransfers as $bankTransfer)
                                                    <option value="{{ $bankTransfer->id }}"
                                                        {{ old('bank_transfer_id') == $bankTransfer->id ? 'selected' : '' }}>
                                                        {{ $bankTransfer->bank_name }} -
                                                        {{ $bankTransfer->account_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('bank_transfer_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="items">Items</label>
                                            <table class="table" id="items-table">
                                                <thead>
                                                    <tr>
                                                        <th>Barang</th>
                                                        <th>Qty</th>
                                                        <th>Unit</th>
                                                        <th>Harga</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select name="items[0][barang_id]"
                                                                class="form-control @error('items.*.barang_id') is-invalid @enderror"
                                                                required>
                                                                <option value="">Select Barang</option>
                                                                @foreach ($barangs as $barang)
                                                                    <option value="{{ $barang->id }}"
                                                                        {{ old('items.0.barang_id') == $barang->id ? 'selected' : '' }}>
                                                                        {{ $barang->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('items.*.barang_id')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="number" name="items[0][qty]"
                                                                class="form-control @error('items.*.qty') is-invalid @enderror"
                                                                value="{{ old('items.0.qty') }}" required>
                                                            @error('items.*.qty')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="text" name="items[0][unit]"
                                                                class="form-control @error('items.*.unit') is-invalid @enderror"
                                                                value="{{ old('items.0.unit') }}" required>
                                                            @error('items.*.unit')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="number" name="items[0][harga]"
                                                                class="form-control @error('items.*.harga') is-invalid @enderror"
                                                                value="{{ old('items.0.harga') }}" required>
                                                            @error('items.*.harga')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger remove-item">Remove</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary" id="add-item">Add
                                                Item</button>
                                        </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="{{ route('data-gudang.barang-keluar.index') }}"
                                        class="btn btn-secondary">Cancel</a>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Page-specific script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = 1;
            const itemTemplate = `
                <tr>
                    <td>
                        <select name="items[%ITEM_COUNT%][barang_id]" class="form-control" required>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}">{{ $barang->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[%ITEM_COUNT%][qty]" class="form-control" required>
                    </td>
                    <td>
                        <input type="text" name="items[%ITEM_COUNT%][unit]" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" name="items[%ITEM_COUNT%][harga]" class="form-control" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </td>
                </tr>
            `;

            document.getElementById('add-item').addEventListener('click', function() {
                const newItem = itemTemplate.replace(/%ITEM_COUNT%/g, itemCount++);
                document.querySelector('#items-table tbody').insertAdjacentHTML('beforeend', newItem);
            });

            document.querySelector('#items-table').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('tr').remove();
                }
            });
        });
    </script>
</body>

</html>
