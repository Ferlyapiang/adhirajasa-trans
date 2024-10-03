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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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

        .select2-selection__rendered {
            line-height: 2.5 !important;
        }

        .select2-container .select2-selection--single {
            height: 50px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
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
                                        <div class="form-group">
                                            <label for="nomor_surat_jalan">Nomor Surat Jalan</label>
                                            <input type="text" name="nomor_surat_jalan" id="nomor_surat_jalan" placeholder="Nomor Surat Jalan"
                                                class="form-control @error('nomor_surat_jalan') is-invalid @enderror"
                                                value="{{ old('nomor_surat_jalan') }}">
                                            @error('nomor_surat_jalan')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
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
                                                class="form-control @error('gudang_id') is-invalid @enderror"
                                                {{ !is_null($user->warehouse_id) ? 'readonly' : '' }} required>
                                                <option value="">Select Gudang</option>
                                                @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ $user->warehouse_id == $warehouse->id ? 'selected' : '' }}>
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
                                                class="form-control select2 @error('customer_id') is-invalid @enderror" required>
                                                <option value="" disabled selected>Select Pemilik Barang</option>
                                                <!-- Options will be dynamically populated here -->
                                            </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label for="shipping_option">Pilih Opsi Pengiriman</label>
                                            <select name="shipping_option" id="shipping_option" class="form-control" onchange="toggleFieldsKirim()">
                                                <option value="">Pilih Opsi Pengiriman</option>
                                                <option value="kirim">Kirim</option>
                                                <option value="takeout">Take Out</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="mobilField" style="display: none;">
                                            <label for="type_mobil_id">Jenis Mobil</label>
                                            <select name="type_mobil_id" id="type_mobil_id" class="form-control">
                                                <option value="">Pilih Jenis Mobil</option>
                                                @foreach ($typeMobilOptions as $typeMobil)
                                                <option value="{{ $typeMobil->id }}">{{ $typeMobil->type }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group" id="hargaKirimField" style="display: none;">
                                            <label for="harga_kirim_barang">Harga Kirim Barang</label>
                                            <input type="text" id="display_harga_kirim_barang " class="form-control @error('harga_kirim_barang') is-invalid @enderror" oninput="formatRupiah(this, 'harga_kirim_barang')">
                                            <input type="hidden" name="harga_kirim_barang" id="harga_kirim_barang">
                                            @error('harga_kirim_barang')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="select_type">Pilih Tipe</label>
                                            <select name="select_type" id="select_type" class="form-control" required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="nomer_polisi">Nomer Polisi</option>
                                                <option value="nomer_container">Nomer Container</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="nomer_polisi_field" style="display:none;">
                                            <label for="nomer_polisi">Nomer Polisi</label>
                                            <input type="text" name="nomer_polisi" id="nomer_polisi"
                                                class="form-control @error('nomer_polisi') is-invalid @enderror"
                                                value="{{ old('nomer_polisi') }}">
                                            @error('nomer_polisi')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Nomer Container field -->
                                        <div class="form-group" id="nomer_container_field" style="display:none;">
                                            <label for="nomer_container">Nomer Container</label>
                                            <input type="text" name="nomer_container" id="nomer_container"
                                                class="form-control @error('nomer_container') is-invalid @enderror"
                                                value="{{ old('nomer_container') }}">
                                            @error('nomer_container')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="ada_lembur">Apakah Ada Lembur untuk Bongkar Barang?</label>
                                            <select id="ada_lembur" class="form-control" onchange="toggleLembur()">
                                                <option value="tidak">Tidak</option>
                                                <option value="ya">Ya</option>
                                            </select>
                                        </div>

                                        <div id="lembur_section" style="display: none;">
                                            <div class="form-group">
                                                <label for="harga_lembur">Harga Lembur</label>
                                                <input type="text" id="display_harga_lembur" class="form-control" oninput="formatRupiah(this, 'harga_lembur')">
                                                <input type="hidden" name="harga_lembur" id="harga_lembur">
                                            </div>
                                        </div>

                                        <h2>Items</h2>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#itemModal">Add
                                            Item</button>
                                        <input type="hidden" name="items" id="items-input" value="[]">
                                        <div class="table-responsive">
                                            <table id="items-table">
                                                <thead>
                                                    <tr>
                                                        <th>Nomer Ref</th>
                                                        <th>Nama Barang</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>

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
                        <label for="item_joc_number">Job Order</label>
                        <input type="text" id="item_joc_number" class="form-control" placeholder="Auto-generated" readonly>
                    </div>
                    <div class="form-group">
                        <label for="item_name">Nama Barang</label>
                        <select id="item_name" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            <!-- Option barang akan ditambahkan di sini -->
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="item_qty">Quantity</label>
                                <input type="number" id="item_qty" class="form-control" required>
                                <span id="quantity-warning" class="text-danger" style="display: none;">Hati-hati Quantity lebih besar dari Sisa Barang.</span>
                            </div>
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
                    <div class="form-group" style="display: none;">
                        <label for="item_barang_masuk_id">Barang Masuk ID</label>
                        <input type="text" id="item_barang_masuk_id" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add-item-btn" disabled>Add Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
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
                        <label for="edit_item_joc_number">Job Order</label>
                        <input type="text" id="edit_item_joc_number" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_item_name">Nama Barang</label>
                        <select id="edit_item_name" class="form-control" required disabled>
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            <!-- Option barang akan ditambahkan di sini -->
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="edit_item_qty">Quantity</label>
                                <input type="number" id="edit_item_qty" class="form-control" required>
                                <span id="edit_quantity-warning" class="text-danger" style="display: none;">Hati-hati Quantity lebih besar dari Sisa Barang.</span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_item_unit">Unit</label>
                                    <input type="text" id="edit_item_unit" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="edit_item_sisa_barang">Sisa Barang</label>
                                    <input type="number" id="edit_item_sisa_barang" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="edit_item_barang_masuk_id">Barang Masuk ID</label>
                        <input type="text" id="edit_item_barang_masuk_id" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-edit-item-btn" disabled>Save Changes</button>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Accounting.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#item_name, #edit_item_name').select2({
                placeholder: 'Pilih Nama Barang',
                allowClear: true
            });
            $('#customer_id').select2({
                placeholder: 'Select Pemilik Barang', // Placeholder text
                allowClear: true // Allow clearing of the selection
            });
            let items = [];
            let nextJocNumber = 1;

            function validateQuantity() {
                let qty = parseFloat($('#item_qty').val()) || 0;
                let sisa = parseFloat($('#item_sisa_barang').val()) || 0;

                if (qty > sisa) {
                    $('#quantity-warning').show();
                } else {
                    $('#quantity-warning').hide();
                }
            }

            $('#item_qty, #item_sisa_barang').on('input', function() {
                validateQuantity();
            });

            validateQuantity();

            function parseCurrency(value) {
                return parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
            }

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
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editItem(${index})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">Remove</button>
                </td>
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
                const itemJocNumber = $('#item_joc_number').val();
                const itemSisaBarang = $('#item_sisa_barang').val();
                const itemBarangMasukID = $('#item_barang_masuk_id').val();

                if (!itemId) {
                    alert('Please select a valid item.');
                    return;
                }

                const itemName = $('#item_name option:selected').text();

                const itemExists = items.some(item =>
                    item.barang_id === parseInt(itemId, 10) &&
                    item.name === itemName &&
                    item.no_ref === itemJocNumber
                );

                if (itemExists) {
                    alert('Barang telah ada dalam daftar!');
                    return;
                }

                items.push({
                    barang_id: parseInt(itemId, 10),
                    no_ref: itemJocNumber,
                    qty: itemQty,
                    unit: itemUnit,
                    barang_masuk_id: parseInt(itemBarangMasukID, 10),
                    name: itemName,
                    sisa_barang: itemSisaBarang
                });

                $('#item_name option:selected').remove();

                nextJocNumber++;

                updateItemsTable();

                $('#item_name').val('');
                $('#item_qty').val('');
                $('#item_unit').val('');
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
                            console.log(response);
                            if (response.customers && Array.isArray(response.customers)) {
                                response.customers.forEach(customer => {
                                    $('#customer_id').append(
                                        `<option value="${customer.id}">${customer.name}</option>`
                                    );
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error fetching customers:', textStatus, errorThrown);
                            $('#customer_id').append('<option value="">Failed to load customers</option>');
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
                            const itemsDropdown = $('#item_name, #edit_item_name');
                            itemsDropdown.empty(); // Clear existing options
                            itemsDropdown.append('<option value="" disabled selected>Pilih Nama Barang</option>');

                            response.items.forEach(item => {
                                itemsDropdown.append(
                                    `<option value="${item.barang_id}" data-unit="${item.unit}" data-joc-number="${item.joc_number}" data-barang-masuk-id="${item.barang_masuk_id}" data-sisa-barang="${item.qty}">${item.barang_name}</option>`
                                );
                            });

                            // Refresh the Select2 dropdown to reflect new options
                            itemsDropdown.trigger('change');

                            // Reset other input fields
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
                $('#item_joc_number').val(jocNumber);
                $('#item_barang_masuk_id').val(barangMasukId);
                $('#item_sisa_barang').val(sisaBarang);
            });

            $('#barang-keluar-form').submit(function() {
                $('#items-input').val(JSON.stringify(items));
            });

            window.editItem = function(index) {
                const item = items[index];

                $('#edit_item_joc_number').val(item.no_ref);
                $('#edit_item_qty').val(item.qty);
                $('#edit_item_unit').val(item.unit);
                $('#edit_item_sisa_barang').val(item.sisa_barang);
                $('#edit_item_barang_masuk_id').val(item.barang_masuk_id);

                $('#edit_item_name').val(item.barang_id).trigger('change');

                $('#save-edit-item-btn').data('index', index);

                $('#editItemModal').modal('show');
            };

            $('#save-edit-item-btn').click(function() {
                const index = $(this).data('index');
                const itemQty = parseFloat($('#edit_item_qty').val()) || 0;

                items[index].qty = itemQty;
                items[index].sisa_barang = $('#edit_item_sisa_barang').val();

                updateItemsTable();
                $('#editItemModal').modal('hide');
            });

            $('#edit_item_qty, #edit_item_sisa_barang').on('input', function() {
                validateEditQuantity();
            });

            function validateEditQuantity() {
                let qty = parseFloat($('#edit_item_qty').val()) || 0;
                let sisa = parseFloat($('#edit_item_sisa_barang').val()) || 0;

                if (qty > sisa) {
                    $('#edit_quantity-warning').show();
                } else {
                    $('#edit_quantity-warning').hide();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var warehouseId = "{{ $user->warehouse_id }}";

            function loadCustomers(warehouseId) {
                if (warehouseId) {
                    fetch(`/api/customers/${warehouseId}`)
                        .then(response => response.json())
                        .then(data => {
                            let customerSelect = document.getElementById('customer_id');
                            customerSelect.innerHTML = '<option value="">Select Pemilik Barang</option>'; // Reset options
                            data.forEach(function(customer) {
                                customerSelect.innerHTML += `<option value="${customer.id}">${customer.name}</option>`;
                            });
                        })
                        .catch(error => console.error('Error fetching customers:', error));
                }
            }

            if (warehouseId) {
                loadCustomers(warehouseId);
            }

            document.getElementById('gudang_id').addEventListener('change', function() {
                var selectedWarehouse = this.value;
                loadCustomers(selectedWarehouse);
            });
        });

        document.getElementById('harga_kirim_barang').addEventListener('input', function(event) {
            let value = this.value;

            value = value.replace(/[^0-9.]/g, '');

            if ((value.match(/\./g) || []).length > 1) {
                value = value.replace(/\.(?=.*\.)/, '');
            }

            if (value.indexOf('.') > -1) {
                value = value.substring(0, value.indexOf('.') + 3);
            }

            this.value = value;
        });

        document.addEventListener('DOMContentLoaded', function() {
            var selectType = document.getElementById('select_type');
            var nomerPolisiField = document.getElementById('nomer_polisi_field');
            var nomerContainerField = document.getElementById('nomer_container_field');


            function toggleFields() {
                var selectedValue = selectType.value;

                if (selectedValue === 'nomer_polisi') {
                    nomerPolisiField.style.display = 'block';
                    nomerContainerField.style.display = 'none';
                } else if (selectedValue === 'nomer_container') {
                    nomerPolisiField.style.display = 'none';
                    nomerContainerField.style.display = 'block';
                } else {
                    nomerPolisiField.style.display = 'none';
                    nomerContainerField.style.display = 'none';
                }
            }

            toggleFields();

            selectType.addEventListener('change', toggleFields);
        });


        function toggleFieldsKirim() {
            const shippingOption = document.getElementById('shipping_option').value;
            const mobilField = document.getElementById('mobilField');
            const hargaKirimField = document.getElementById('hargaKirimField');

            if (shippingOption === 'kirim') {
                mobilField.style.display = 'block';
                hargaKirimField.style.display = 'block';
            } else {
                mobilField.style.display = 'none';
                hargaKirimField.style.display = 'none';
            }
        }

        function toggleLembur() {
            let select = document.getElementById('ada_lembur');
            let lemburSection = document.getElementById('lembur_section');
            if (select.value === 'ya') {
                lemburSection.style.display = 'block';
            } else {
                lemburSection.style.display = 'none';
            }
        }

        function formatRupiah(displayInput, hiddenInputId) {
            let angka = displayInput.value.replace(/[^,\d]/g, ''); // Hanya angka
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
            displayInput.value = formatted.replace('IDR', 'Rp.');

            document.getElementById(hiddenInputId).value = angka;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const itemQtyInput = document.getElementById('item_qty');
            const itemSisaBarangInput = document.getElementById('item_sisa_barang');
            const quantityWarning = document.getElementById('quantity-warning');
            const addItemButton = document.getElementById('add-item-btn');

            // Fungsi untuk memeriksa jumlah dan mengatur tombol
            function checkQuantity() {
                const qty = parseInt(itemQtyInput.value, 10);
                const sisa = parseInt(itemSisaBarangInput.value, 10);

                if (qty > sisa) {
                    quantityWarning.style.display = 'block';
                    addItemButton.disabled = true; // Nonaktifkan tombol jika quantity lebih besar
                } else {
                    quantityWarning.style.display = 'none';
                    addItemButton.disabled = false; // Aktifkan tombol jika valid
                }
            }

            // Tambahkan event listener pada input qty
            itemQtyInput.addEventListener('input', checkQuantity);
            itemSisaBarangInput.addEventListener('input', checkQuantity); // Jika sisa barang juga diubah
        });

        document.addEventListener("DOMContentLoaded", function() {
            const editItemQtyInput = document.getElementById('edit_item_qty');
            const editItemSisaBarangInput = document.getElementById('edit_item_sisa_barang');
            const editQuantityWarning = document.getElementById('edit_quantity-warning');
            const saveEditItemButton = document.getElementById('save-edit-item-btn');

            function checkEditQuantity() {
                const qty = parseInt(editItemQtyInput.value, 10);
                const sisa = parseInt(editItemSisaBarangInput.value, 10);

                if (qty > sisa) {
                    editQuantityWarning.style.display = 'block';
                    saveEditItemButton.disabled = true;
                } else {
                    editQuantityWarning.style.display = 'none';
                    saveEditItemButton.disabled = false;
                }
            }

            editItemQtyInput.addEventListener('input', checkEditQuantity);
            editItemSisaBarangInput.addEventListener('input', checkEditQuantity);
        });
    </script>


</body>

</html>