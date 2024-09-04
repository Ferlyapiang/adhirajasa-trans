<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang Keluar</title>

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
                            <h1 class="m-0">Edit Barang Keluar</h1>
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
                                            <label for="tanggal_keluar">Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                                class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                                value="{{ old('tanggal_keluar', $barangKeluar->tanggal_keluar) }}"
                                                required>
                                            @error('tanggal_keluar')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="gudang_id">Gudang</label>
                                            <select name="gudang_id" id="gudang_id"
                                                class="form-control @error('gudang_id') is-invalid @enderror"
                                                style="pointer-events: none; background-color: #e9ecef;" required>
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
                                                class="form-control @error('customer_id') is-invalid @enderror"
                                                style="pointer-events: none; background-color: #e9ecef;" required>
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
                                            <label for="nomer_container">Nomor Container</label>
                                            <input type="text" name="nomer_container" id="nomer_container"
                                                class="form-control @error('nomer_container') is-invalid @enderror"
                                                value="{{ old('nomer_container', $barangKeluar->nomer_container) }}">
                                            @error('nomer_container')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nomer_polisi">Nomor Polisi</label>
                                            <input type="text" name="nomer_polisi" id="nomer_polisi"
                                                class="form-control @error('nomer_polisi') is-invalid @enderror"
                                                value="{{ old('nomer_polisi', $barangKeluar->nomer_polisi) }}">
                                            @error('nomer_polisi')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="bank_transfer_id">Bank Transfer</label>
                                            <select name="bank_transfer_id" id="bank_transfer_id"
                                                class="form-control @error('bank_transfer_id') is-invalid @enderror">
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
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#itemModal">
                                            Add Item
                                        </button>

                                        <input type="hidden" name="items" id="items-input" value="[]">


                                        <!-- Items Table -->
                                        <table class="table" id="items-table">
                                            <thead>
                                                <tr>
                                                    <th>Nomer Ref</th>
                                                    <th>Nama Barang</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Harga</th>
                                                    <th>Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($barangKeluar->items as $item)
                                                    <tr>
                                                        <td>{{ $item->no_ref }}</td>
                                                        <td>{{ $item->barang->nama_barang }}</td>
                                                        <td>{{ $item->qty }}</td>
                                                        <td>{{ $item->unit }}</td>
                                                        <td>Rp. {{ number_format($item->harga) }}</td>
                                                        <td>Rp. {{ number_format($item->total_harga) }}</td>
                                                        {{-- <td>{{ $item->barang_id }}</td> --}}
                                                        <td data-barang-id="{{ $item->barang_id }}">{{ $item->barang_id }}</td>
                                                        <td data-barang-id="{{ $item->barang_masuk_id }}">{{ $item->barang_masuk_id }}</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-danger remove-item">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <br><br>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Save</button>
                                            <a href="{{ route('data-gudang.barang-keluar.index') }}"
                                                class="btn btn-secondary">Cancel</a>
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

        <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

        <!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modal_barang_id" class="form-label">Barang</label>
                            <select class="form-select" id="modal_barang_id">
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="modal_no_ref" class="form-label">No. Ref</label>
                            <input type="text" class="form-control" id="modal_no_ref">
                        </div>
                        <div class="mb-3">
                            <label for="modal_qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="modal_qty">
                        </div>
                        <div class="mb-3">
                            <label for="modal_unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="modal_unit">
                        </div>
                        <div class="mb-3">
                            <label for="modal_harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="modal_harga"> <!-- Change to text -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addItemButton">Add Item</button>
                    </div>
                </div>
            </div>
        </div>


        <script>
            $(document).ready(function() {
                //     function formatCurrency(amount) {
                //     let number = parseFloat(amount);
                //     if (isNaN(number)) return 'Rp. 0';
                //     return `Rp. ${number.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).replace(/,/g, '.')}`;
                // }

                function formatCurrency(amount) {
                    let number = parseFloat(amount);
                    if (isNaN(number)) return 'Rp. 0';
                    return `Rp. ${number.toLocaleString('id-ID', { minimumFractionDigits: 0 })}`;
                }


                function parseCurrency(value) {
                    return parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
                }

                function toRawNumber(value) {
                    return parseFloat(value.replace(/[^0-9]/g, '')) || 0;
                }

                function toInteger(value) {
                    return parseInt(value, 10) || 0; // Konversi ke integer
                }


                // Function to update the hidden input with table data
                function updateItemsInput() {
                    let items = [];
                    $('#items-table tbody tr').each(function() {
                        let row = $(this);
                        let barangIdText = row.find('td:eq(6)').text();
                        let barangId = parseInt(barangIdText, 10); 
                        let barangMasukIdText = row.find('td:eq(7)').text();
                        let barangMasukId = parseInt(barangMasukIdText, 10);
                        let item = {
                            barang_id: barangId, 
                            no_ref: row.find('td:eq(0)').text(),
                            qty: toInteger(row.find('td:eq(2)').text()),
                            unit: row.find('td:eq(3)').text(),
                            harga: toRawNumber(row.find('td:eq(4)').text()),
                            total_harga: toRawNumber(row.find('td:eq(5)').text()),
                            barang_masuk_id: barangMasukId,
                        };

                        items.push(item);
                    });
                    $('#items-input').val(JSON.stringify(items));
                }



                // Initialize hidden input with existing table data
                updateItemsInput();

                // Handle adding new item
                $('#addItemButton').on('click', function() {
                    let barangId = $('#modal_barang_id').val();
                    let barangName = $('#modal_barang_id option:selected').text();
                    let noRef = $('#modal_no_ref').val();
                    let qty = toInteger($('#modal_qty').val()); // Pastikan konversi ke integer
                    let unit = $('#modal_unit').val();
                    let harga = parseCurrency($('#modal_harga').val()); // Pastikan konversi harga
                    let total = (qty * harga).toFixed(2);

                    // Format harga dan total untuk tampilan
                    let formattedHarga = formatCurrency(harga);
                    let formattedTotal = formatCurrency(total);

                    // Tambahkan baris baru ke tabel
                    let row = `<tr>
        <td>${noRef}</td>
        <td data-barang-id="${barangId}">${barangName}</td>
        <td>${qty}</td> <!-- qty sebagai integer -->
        <td>${unit}</td>
        <td>${formattedHarga}</td>
        <td>${formattedTotal}</td>
        <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
    </tr>`;

                    $('#items-table tbody').append(row);

                    // Update input tersembunyi dengan data tabel baru
                    updateItemsInput();

                    // Bersihkan input modal setelah menambahkan
                    $('#modal_barang_id').val('');
                    $('#modal_no_ref').val('');
                    $('#modal_qty').val('');
                    $('#modal_unit').val('');
                    $('#modal_harga').val('');

                    $('#itemModal').modal('hide');
                });


                // Remove item from the table and update the hidden input
                $('#items-table').on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    // Update the hidden input with the remaining table data
                    updateItemsInput();
                });

                // Ensure input fields are formatted as currency
                $('#modal_harga').on('input', function() {
                    let value = $(this).val();
                    let parsedValue = parseCurrency(value);
                    $(this).val(formatCurrency(parsedValue));
                });
            });
        </script>
</body>

</html>
