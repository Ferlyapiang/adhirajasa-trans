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
                                                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                        <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}
                                                        </td>
                                                        {{-- <td>{{ $item->barang_id }}</td> --}}
                                                        <td style="display: none" data-barang-id="{{ $item->barang_masuk_id }}">
                                                            {{ $item->barang_masuk_id }}</td>
                                                        <td style="display: none" data-barang-id="{{ $item->barang_id }}">
                                                            {{ $item->barang_id }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning edit-item">Edit</button>
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
                        <div class="form-group">
                            <label for="modal_no_ref" class="form-label">No. Ref</label>
                            <input type="text" class="form-control" id="modal_no_ref" placeholder="Auto-generated" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modal_barang_id" class="form-label">Barang</label>
                            <select class="form-control" id="modal_barang_id">
                                <option value="" data-jenis="" data-barang-masuk-id="">-- Pilih Barang --
                                </option>
                                @foreach ($barangs as $barang)
                                    @foreach ($groupedBarangMasukItems as $barangMasukId => $items)
                                        @foreach ($items as $item)
                                            @if ($barang->id === $item->barang_id)
                                                <option value="{{ $barang->id }}"
                                                    data-jenis="{{ $barang->jenis }}"
                                                    data-barang-masuk-id="{{ $item->barang_masuk_id }}">
                                                    {{ $barang->nama_barang }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modal_qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="modal_qty">
                        </div>
                        <div class="form-group">
                            <label for="modal_unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="modal_unit" readonly>
                            <!-- Make it readonly -->
                        </div>
                        <div class="form-group">
                            <label for="modal_harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="modal_harga"> <!-- Change to text -->
                        </div>
                        <input type="hidden" id="modal_barang_masuk_id">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addItemButton">Add Item</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Item -->
        <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_modal_no_ref" class="form-label">No. Ref</label>
                            <input type="text" class="form-control" id="edit_modal_no_ref" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_modal_barang_id" class="form-label">Barang</label>
                            <select class="form-control" id="edit_modal_barang_id" disabled>
                                <option value="" data-jenis="" data-barang-masuk-id="">-- Pilih Barang --</option>
                                @foreach ($barangs as $barang)
                                    @foreach ($groupedBarangMasukItems as $barangMasukId => $items)
                                        @foreach ($items as $item)
                                            @if ($barang->id === $item->barang_id)
                                                <option value="{{ $barang->id }}" data-jenis="{{ $barang->jenis }}" data-barang-masuk-id="{{ $item->barang_masuk_id }}">
                                                    {{ $barang->nama_barang }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_modal_qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_modal_qty">
                        </div>
                        <div class="form-group">
                            <label for="edit_modal_unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="edit_modal_unit" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_modal_harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="edit_modal_harga">
                        </div>
                        <input type="hidden" id="edit_modal_barang_masuk_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEditItemButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        


        <script>
            $(document).ready(function() {
                const barangSelect = $('#modal_barang_id');
                const noRefInput = $('#modal_no_ref');
                const barangMasukData = @json($barangMasuks); // Convert PHP data to JSON

                // Update No. Ref input when barang_id changes
                barangSelect.on('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const barangMasukId = $(selectedOption).data('barang-masuk-id');

                    if (barangMasukId && barangMasukData[barangMasukId]) {
                        noRefInput.val(barangMasukData[barangMasukId].joc_number);
                    } else {
                        noRefInput.val(''); // Clear value if not found
                    }

                    const jenis = $(selectedOption).data('jenis');
                    $('#modal_unit').val(jenis ? jenis : '');
                    $('#modal_barang_masuk_id').val(barangMasukId ? barangMasukId : '');
                });

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
                    return parseInt(value, 10) || 0;
                }

                function updateItemsInput() {
                    let items = [];
                    $('#items-table tbody tr').each(function() {
                        let row = $(this);
                        let item = {
                            barang_id: toInteger(row.find('td:eq(7)')
                        .text()), // Assuming barang_id is in the 8th column
                            no_ref: row.find('td:eq(0)').text(),
                            qty: toInteger(row.find('td:eq(2)').text()),
                            unit: row.find('td:eq(3)').text(),
                            harga: toRawNumber(row.find('td:eq(4)').text()),
                            total_harga: toRawNumber(row.find('td:eq(5)').text()),
                            barang_masuk_id: toInteger(row.find('td:eq(6)')
                            .text()) // Assuming barang_masuk_id is in the 7th column
                        };
                        items.push(item);
                    });
                    $('#items-input').val(JSON.stringify(items));
                }

                $('#addItemButton').on('click', function() {
                    let barangId = barangSelect.val();
                    let barangName = barangSelect.find('option:selected').text();
                    let noRef = $('#modal_no_ref').val();
                    let qty = toInteger($('#modal_qty').val());
                    let unit = $('#modal_unit').val();
                    let harga = parseCurrency($('#modal_harga').val());
                    let total = (qty * harga).toFixed(2);
                    let barangMasukId = $('#modal_barang_masuk_id').val();

                    let formattedHarga = formatCurrency(harga);
                    let formattedTotal = formatCurrency(total);

                    // Check if the item with the same barang_id already exists in the table
                    let itemExists = false;
                    $('#items-table tbody tr').each(function() {
                        let jocNumber = $(this).find('td:eq(0)').text();
                        let rowBarangId = $(this).find('td:eq(7)')
                    .text(); // Assuming barang_id is in the 8th column
                        if (toInteger(rowBarangId) === toInteger(barangId) && jocNumber === noRef) {
                            itemExists = true;
                            return false;
                        }
                    });

                    if (itemExists) {
                        alert('Barang tersebut sudah ada di tabel.');
                        return;
                    }

                    let row = `<tr>
            <td>${noRef}</td>
            <td data-barang-id="${barangId}">${barangName}</td>
            <td>${qty}</td>
            <td>${unit}</td>
            <td>${formattedHarga}</td>
            <td>${formattedTotal}</td>
            <td style="display: none">${barangMasukId}</td>
            <td style="display: none">${barangId}</td>
            <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
        </tr>`;

                    $('#items-table tbody').append(row);
                    updateItemsInput();

                    // Clear modal input fields
                    barangSelect.val('');
                    noRefInput.val('');
                    $('#modal_qty').val('');
                    $('#modal_unit').val('');
                    $('#modal_harga').val('');
                    $('#modal_barang_masuk_id').val('');

                    $('#itemModal').modal('hide');
                });

                $('#items-table').on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    updateItemsInput();
                });

                // Ensure harga input is formatted as currency
                $('#modal_harga').on('input', function() {
                    let value = $(this).val();
                    let parsedValue = parseCurrency(value);
                    $(this).val(formatCurrency(parsedValue));
                });

                let currentEditingRow;
                $('#items-table').on('click', '.edit-item', function() {
        currentEditingRow = $(this).closest('tr'); // Get the current row to edit

        let barangId = currentEditingRow.find('td:eq(7)').text(); // barang_id in 8th column
        let barangName = currentEditingRow.find('td:eq(1)').text();
        let noRef = currentEditingRow.find('td:eq(0)').text();
        let qty = currentEditingRow.find('td:eq(2)').text();
        let unit = currentEditingRow.find('td:eq(3)').text();
        let harga = currentEditingRow.find('td:eq(4)').text();
        let barangMasukId = currentEditingRow.find('td:eq(6)').text(); // barang_masuk_id in 7th column

        // Set modal fields with row data
        $('#edit_modal_barang_id').val(barangId).change();
        $('#edit_modal_no_ref').val(noRef);
        $('#edit_modal_qty').val(qty);
        $('#edit_modal_unit').val(unit);
        $('#edit_modal_harga').val(harga);
        $('#edit_modal_barang_masuk_id').val(barangMasukId);

        // Show the modal
        $('#editItemModal').modal('show');
    });

    // Save changes when 'Save changes' button is clicked
    $('#saveEditItemButton').on('click', function() {
        let barangId = $('#edit_modal_barang_id').val();
        let barangName = $('#edit_modal_barang_id option:selected').text();
        let noRef = $('#edit_modal_no_ref').val();
        let qty = toInteger($('#edit_modal_qty').val());
        let unit = $('#edit_modal_unit').val();
        let harga = parseCurrency($('#edit_modal_harga').val());
        let total = (qty * harga).toFixed(2);
        let barangMasukId = $('#edit_modal_barang_masuk_id').val();

        let formattedHarga = formatCurrency(harga);
        let formattedTotal = formatCurrency(total);

        // Update the current row with the edited values
        currentEditingRow.find('td:eq(0)').text(noRef);
        currentEditingRow.find('td:eq(1)').text(barangName);
        currentEditingRow.find('td:eq(2)').text(qty);
        currentEditingRow.find('td:eq(3)').text(unit);
        currentEditingRow.find('td:eq(4)').text(formattedHarga);
        currentEditingRow.find('td:eq(5)').text(formattedTotal);
        currentEditingRow.find('td:eq(6)').text(barangMasukId); // Update barang_masuk_id (7th column)
        currentEditingRow.find('td:eq(7)').text(barangId); // Update barang_id (8th column)

        // Close modal after saving
        $('#editItemModal').modal('hide');
    });

    // Ensure harga input is formatted as currency in the edit modal
    $('#edit_modal_harga').on('input', function() {
        let value = $(this).val();
        let parsedValue = parseCurrency(value);
        $(this).val(formatCurrency(parsedValue));
    });
            });
        </script>

</body>

</html>
