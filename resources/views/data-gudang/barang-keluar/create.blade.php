<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Barang Keluar</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Custom CSS -->
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
                                                    {{ $warehouse->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('gudang_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_id">Pemilik Barang</label>
                                            <select name="customer_id" id="customer_id"
                                                class="form-control @error('customer_id') is-invalid @enderror" required>
                                                <option value="">Select Pemilik Barang</option>
                                                <!-- Options akan diisi melalui JavaScript -->
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

                                        <h2>Items</h2>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#itemModal">Add
                                            Item</button>
                                        <input type="hidden" name="items" id="items-input" value="[]">
                                        <!-- Items Table -->
                                        <table id="items-table">
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
                                                <!-- Items will be added here dynamically -->
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

    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
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
                        <label for="item_joc_number">JOC Number</label>
                        <input type="text" id="item_joc_number" class="form-control" placeholder="Auto-generated" readonly>
                    </div>
                    <div class="form-group">
                        <label for="item_name">Nama Barang</label>
                        <select id="item_name" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            <!-- Options will be populated based on selected customer -->
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <!-- Quantity and Unit Fields (Left Side) -->
                            <div class="col-md-6">
                                <label for="item_qty">Quantity</label>
                                <input type="number" id="item_qty" class="form-control" required>
                                <span id="quantity-warning" class="text-danger" style="display: none;">Hati-hati Quantity lebih besar dari Sisa Barang.</span>
                            </div>

                            <!-- Unit and Sisa Barang Fields (Right Side) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_unit">Unit</label>
                                    <input type="text" id="item_unit" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="item_sisa_barang">Sisa Barang</label>
                                    <input type="number" id="item_sisa_barang" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="item_price">Price</label>
                        <input type="text" id="item_price" class="form-control" required>
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="item_barang_masuk_id">Barang Masuk ID</label>
                        <input type="text" id="item_barang_masuk_id" class="form-control" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add-item-btn">Add Item</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>


    <script>
    $(document).ready(function() {
    let items = [];
    let nextJocNumber = 1; // Initialize with the starting number

    function validateQuantity() {
        let qty = parseFloat($('#item_qty').val()) || 0;
        let sisa = parseFloat($('#item_sisa_barang').val()) || 0;

        if (qty > sisa) {
            $('#quantity-warning').show();
        } else {
            $('#quantity-warning').hide();
        }
    }

    // Validate quantity on input change
    $('#item_qty, #item_sisa_barang').on('input', function() {
        validateQuantity();
    });

    // Initialize validation
    validateQuantity();

    function formatCurrency(amount) {
        let number = parseFloat(amount);
        if (isNaN(number)) return 'Rp. 0';
        return `Rp. ${number.toLocaleString('id-ID', { minimumFractionDigits: 0 })}`;
    }

    function parseCurrency(value) {
        return parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
    }

    $('#item_price').on('input', function() {
        let value = $(this).val();
        let parsedValue = parseCurrency(value);
        $(this).val(formatCurrency(parsedValue));
    });

    function updateItemsTable() {
        let itemsTableBody = $('#items-table tbody');
        itemsTableBody.empty();
        items.forEach((item, index) => {
            itemsTableBody.append(`
                <tr>
                    <td>${item.no_ref}</td>
                    <td>${item.name}</td>
                    <td>${item.qty}</td>
                    <td>${item.unit}</td>
                    <td>${formatCurrency(item.harga)}</td>
                    <td>${formatCurrency(item.total_harga)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">Remove</button></td>
                </tr>
            `);
        });
        $('#items-input').val(JSON.stringify(items));
    }

    window.removeItem = function(index) {
        items.splice(index, 1);
        updateItemsTable();
    };

    $('#add-item-btn').click(function() {
        const itemId = $('#item_name').val();
        const itemQty = parseFloat($('#item_qty').val()) || 0;
        const itemUnit = $('#item_unit').val();
        const itemPrice = parseCurrency($('#item_price').val());
        const itemTotal = itemQty * itemPrice;
        const itemJocNumber = $('#item_joc_number').val();
        const itemSisaBarang = $('#item_sisa_barang').val();
        const itemBarangMasukID = $('#item_barang_masuk_id').val();

        if (!itemId) {
            alert('Please select a valid item.');
            return;
        }

        const itemExists = items.some(item => item.barang_id === itemId);
        if (itemExists) {
            alert('Item already added.');
            return;
        }

        items.push({
            barang_id: itemId, 
            no_ref: itemJocNumber,  // Ensure JOC Number is added here
            qty: itemQty,
            unit: itemUnit,
            harga: itemPrice,
            total_harga: itemTotal,
            barang_masuk_id: itemBarangMasukID,
            name: $('#item_name option:selected').text(),
            sisa_barang: itemSisaBarang
        });

        nextJocNumber++;

        updateItemsTable();
        $('#itemModal').modal('hide');

        $('#item_name').val('');
        $('#item_qty').val('');
        $('#item_unit').val('');
        $('#item_price').val('');
        $('#item_joc_number').val('');
        $('#item_sisa_barang').val('');
    });

    $('#gudang_id').change(function() {
        const warehouseId = $(this).val();

        $('#customer_id').empty();
        $('#customer_id').append('<option value="">Select Pemilik Barang</option>');

        if (warehouseId) {
            $.ajax({
                url: `/api/customers/${warehouseId}`,
                method: 'GET',
                success: function(response) {
                    response.customers.forEach(customer => {
                        $('#customer_id').append(`<option value="${customer.id}">${customer.name}</option>`);
                    });
                },
                error: function() {
                    $('#customer_id').append('<option value="">No customers found</option>');
                }
            });
        }
    });

    $('#customer_id').change(function() {
        const customerId = $(this).val();
        const warehouseId = $('#gudang_id').val();

        if (customerId && warehouseId) {
            $.ajax({
                url: `/api/items/${customerId}/${warehouseId}`,
                method: 'GET',
                success: function(response) {
                    const itemsDropdown = $('#item_name');
                    itemsDropdown.empty();
                    itemsDropdown.append('<option value="" disabled selected>Pilih Nama Barang</option>');
                    response.items.forEach(item => {
                        itemsDropdown.append(`<option value="${item.barang_id}" data-unit="${item.unit}" data-joc-number="${item.joc_number}" data-barang-masuk-id="${item.barang_masuk_id}" data-sisa-barang="${item.qty}">${item.barang_name}</option>`);
                    });

                    $('#item_unit').val('').prop('readonly', true);
                    $('#item_joc_number').val('Auto-generated');
                    $('#item_barang_masuk_id').val('');
                    $('#item_sisa_barang').val('');
                }
            });
        }
    });

    $('#item_name').change(function() {
        const selectedOption = $(this).find('option:selected');
        const unit = selectedOption.data('unit');
        const jocNumber = selectedOption.data('joc-number');
        const barangMasukId = selectedOption.data('barang-masuk-id');
        const sisaBarang = selectedOption.data('sisa-barang');

        $('#item_unit').val(unit).prop('readonly', true);
        $('#item_joc_number').val(jocNumber);  // Set JOC Number correctly
        $('#item_barang_masuk_id').val(barangMasukId);
        $('#item_sisa_barang').val(sisaBarang);
    });

    $('#barang-keluar-form').submit(function() {
        $('#items-input').val(JSON.stringify(items));
    });
});

</script>


</body>

</html>