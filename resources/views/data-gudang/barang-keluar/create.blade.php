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
                                                value="{{ old('nomor_surat_jalan') }}" required>
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
                                            </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label for="shipping_option">Pilih Opsi Pengiriman</label>
                                            <select name="shipping_option" id="shipping_option" class="form-control" onchange="toggleFieldsKirim()" required>
                                                <option value="">Pilih Opsi Pengiriman</option>
                                                <option value="kirim">Kirim</option>
                                                <option value="takeout">Pick Up</option>
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
                                            <input type="text" id="display_harga_kirim_barang" class="form-control @error('harga_kirim_barang') is-invalid @enderror" oninput="formatRupiah(this, 'harga_kirim_barang')">
                                            <input type="hidden" name="harga_kirim_barang" id="harga_kirim_barang">
                                            @error('harga_kirim_barang')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group" id="alamatField" style="display: none;">
                                            <label for="address">Alamat Kirim</label>
                                            <textarea name="address" id="address" placeholder="Alamat" class="form-control @error('address') is-invalid @enderror" rows="4">{{ old('address') }}</textarea>
                                            @error('address')
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
                                        <button id="btn-jo-number" type="button" class="btn btn-primary" data-toggle="modal" data-target="#item_barang_masuk_id_jo">
                                            Add Item Fifo
                                        </button>
                                        <button id="btn-nomer-container" type="button" class="btn btn-primary" data-toggle="modal" data-target="#nomerContainerModal">
                                            Add Item Partai
                                        </button>

                                        <input type="hidden" name="items" id="items-input" value="[]">
                                        <div class="table-responsive">
                                            <table id="items-table" class="table">
                                                <thead>
                                                    <!-- Header tabel akan diupdate -->
                                                </thead>
                                                <tbody>
                                                    <!-- Data tabel akan diupdate -->
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3" style="text-align:right;">Total:</th>
                                                        <th id="totalQuantity"></th>
                                                        <th colspan="4"></th>
                                                    </tr>
                                                </tfoot>
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

    <!-- Modal JO -->
    <div class="modal fade" id="item_barang_masuk_id_jo" tabindex="-1" role="dialog" aria-labelledby="item_barang_masuk_id_joLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="item_barang_masuk_id_joLabel">Add Item (Job Order)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_joc_number_jo">Job Order</label>
                        <input type="text" id="item_joc_number_jo" class="form-control" placeholder="Auto-generated" readonly>
                    </div>
                    <div class="form-group">
                        <label for="item_name_jo">Nama Barang || JO Number</label>
                        <select id="item_name_jo" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            <!-- Option barang akan ditambahkan di sini -->
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="item_qty_jo">Quantity</label>
                                <input type="number" id="item_qty_jo" class="form-control" required>
                                <span id="quantity-warning-jo" class="text-danger" style="display: none;">
                                    Hati-hati Quantity lebih besar dari Sisa Barang.
                                </span>
                                <span id="quantity-warning-jo-status-ngepok" class="text-danger" style="display: none;">
                                    Quantity harus sama dengan Sisa Barang karena status transit.
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_unit_jo">Unit</label>
                                    <input type="text" id="item_unit_jo" class="form-control" readonly>
                                </div>
                                <div class="form-group" hidden>
                                    <label for="item_status_ngepok">Ngepok</label>
                                    <input type="text" id="item_status_ngepok" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="item_sisa_barang_jo">Sisa Barang</label>
                                    <input type="number" id="item_sisa_barang_jo" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="item_barang_masuk_id_jo">Barang Masuk ID</label>
                        <input type="text" id="item_barang_masuk_id_jo class=" form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add-item-btn-jo" disabled>Add Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Container -->
    <div class="modal fade" id="nomerContainerModal" tabindex="-1" role="dialog" aria-labelledby="nomerContainerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nomerContainerModalLabel">Add Item (Container)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_no_container">Nomer JO</label>
                        <input type="text" id="item_no_container" class="form-control" placeholder="Auto-generated" readonly>
                    </div>
                    <div class="form-group">
                        <label for="item_name_container">Nama Barang || Nomer Container</label>
                        <select id="item_name_container" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Barang</option>
                            <!-- Option barang akan ditambahkan di sini -->
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="item_qty_container">Quantity</label>
                                <input type="number" id="item_qty_container" class="form-control" required>
                                <span id="quantity-warning-container" class="text-danger" style="display: none;">
                                    Hati-hati Quantity lebih besar dari Sisa Barang.
                                </span>
                                <span id="quantity-warning-container-status-ngepok" class="text-danger" style="display: none;">
                                    Quantity harus sama dengan Sisa Barang karena status transit.
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="item_unit_container">Unit</label>
                                    <input type="text" id="item_unit_container" class="form-control" readonly>
                                </div>
                                <div class="form-group" hidden>
                                    <label for="item_status_ngepok">Ngepok</label>
                                    <input type="text" id="item_status_ngepok" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="item_sisa_barang_container">Sisa Barang</label>
                                    <input type="number" id="item_sisa_barang_container" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="item_barang_masuk_id_container">Barang Masuk ID</label>
                        <input type="text" id="item_barang_masuk_id_container" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add-item-btn-container" disabled>Add Item</button>
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
                        <label for="edit_item_joc_number_jo">Job Order</label>
                        <input type="text" id="edit_item_joc_number_jo" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_item_name">Nama Barang || JO Number</label>
                        <input type="text" id="edit_item_name" class="form-control" readonly>
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
                                    <label for="edit_item_unit_jo">Unit</label>
                                    <input type="text" id="edit_item_unit_jo" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="edit_item_sisa_barang">Sisa Barang</label>
                                    <input type="number" id="edit_item_sisa_barang" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="edit_item_barang_masuk_id_jo">Barang Masuk ID</label>
                        <input type="text" id="edit_item_barang_masuk_id_jo" class="form-control" readonly>
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
            $('#item_name').select2({
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
                const qty = parseFloat($('#item_qty').val()) || 0;
                const sisa = parseFloat($('#item_sisa_barang').val()) || 0;

                if (qty > sisa) {
                    $('#quantity-warning').show();
                    $('#add-item-btn').prop('disabled', true);
                } else {
                    $('#quantity-warning').hide();
                    $('#add-item-btn').prop('disabled', false);
                }
            }

            $('#item_qty, #item_sisa_barang').on('input', validateQuantity);
            validateQuantity();


            function parseCurrency(value) {
                return parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
            }

            let currentMode = '';

            function updateTableHeader() {
                let tableHead = $('#items-table thead');
                tableHead.empty(); // Kosongkan header tabel

                if (currentMode === 'fifo') {
                    tableHead.append(`
            <tr>
                <th>Nomer JO</th>
                <th>Nama Barang</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Actions</th>
            </tr>
        `);
                } else if (currentMode === 'partai') {
                    tableHead.append(`
            <tr>
                <th>Nomer JO</th>
                <th>Nomer Container</th>
                <th>Nama Barang</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Actions</th>
            </tr>
        `);
                }
            }

            function updateItemsTable() {
                let itemsTableBody = $('#items-table tbody');
                itemsTableBody.empty(); // Hapus semua baris sebelumnya

                items.forEach((item, index) => {
                    let row = `
            <tr>
                <td>${item.no_ref}</td>
        `;

                    if (currentMode === 'partai') {
                        row += `<td>${item.nomer_container || '-'}</td>`;
                    }

                    row += `
                <td>${item.name}</td>
                <td>${item.qty}</td>
                <td>${item.unit}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editItem(${index})">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(${index})">Remove</button>
                </td>
            </tr>
        `;

                    itemsTableBody.append(row);
                });

                // Simpan data ke input hidden untuk pengiriman
                $('#items-input').val(JSON.stringify(items));
            }

            $('#btn-jo-number').on('click', function() {
                currentMode = 'fifo';
                updateTableHeader();
                updateItemsTable();
            });

            $('#btn-nomer-container').on('click', function() {
                currentMode = 'partai';
                updateTableHeader();
                updateItemsTable();
            });


            window.removeItem = function(index) {
                items.splice(index, 1);
                updateItemsTable();
                updateTotalQuantity();
            };

            $('#add-item-btn-jo').click(function() {
                const itemId = $('#item_name_jo').val();
                const itemQty = parseFloat($('#item_qty_jo').val()) || 0;
                const itemUnit = $('#item_unit_jo').val();
                const itemJocNumber = $('#item_joc_number_jo').val();
                const itemSisaBarang = $('#item_sisa_barang_jo').val();
                const itemBarangMasukID = $('#item_barang_masuk_id_jo').val();
                const itemStatusNgepok = $('#item_status_ngepok').val();

                if (!itemId) {
                    alert('Please select a valid item.');
                    return;
                }

                const itemName = $('#item_name_jo option:selected').text();

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
                    nomer_container: '',
                    qty: itemQty,
                    unit: itemUnit,
                    barang_masuk_id: parseInt(itemBarangMasukID, 10),
                    name: itemName,
                    sisa_barang: itemSisaBarang
                });

                $('#item_name_jo option:selected').remove();

                nextJocNumber++;

                updateItemsTable();

                updateTotalQuantity();

                $('#item_joc_number_jo').val('');
                $('#item_name_jo').val('');
                $('#item_qty_jo').val('');
                $('#item_unit_jo').val('');
                $('#item_sisa_barang_jo').val('');
                $('#item_barang_masuk_id_joLabel').val('');
                $('#item_status_ngepok').val('');
            });

            $('#add-item-btn-container').click(function() {
                const itemId = $('#item_name_container').val();
                const itemQty = parseFloat($('#item_qty_container').val()) || 0;
                const itemUnit = $('#item_unit_container').val();
                const itemJocNumber = $('#item_no_container').val();
                const itemSisaBarang = $('#item_sisa_barang_container').val();
                const itemBarangMasukID = $('#item_barang_masuk_id_container').val();
                const itemStatusNgepok = $('#item_status_ngepok').val();

                if (!itemId) {
                    alert('Please select a valid item.');
                    return;
                }

                const itemName = $('#item_name_container option:selected').text();
                const extractedName = itemName.split('||')[1]?.trim();

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
                    nomer_container: extractedName,
                    qty: itemQty,
                    unit: itemUnit,
                    barang_masuk_id: parseInt(itemBarangMasukID, 10),
                    name: itemName,
                    sisa_barang: itemSisaBarang
                });

                $('#item_name_container option:selected').remove();

                nextJocNumber++;

                updateItemsTable();

                updateTotalQuantity();

                $('#item_joc_number_jo').val('');
                $('#item_no_container').val('');
                $('#item_name_container').val('');
                $('#item_qty_container').val('');
                $('#item_unit_container').val('');
                $('#item_sisa_barang_container').val('');
                $('#item_barang_masuk_id_container').val('');
                $('#item_status_ngepok').val('');
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

            let isContainer = false;

            // Event untuk tombol "Add Item Job Order"
            $('#btn-jo-number').click(function() {
                isContainer = false;
                callApi('#item_name_jo', '#item_unit_jo', '#item_joc_number_jo', '#item_barang_masuk_id_jo', '#item_sisa_barang_jo', '#item_status_ngepok');
            });

            // Event untuk tombol "Add Item Container"
            $('#btn-nomer-container').click(function() {
                isContainer = true;
                callApi('#item_name_container', '#item_unit_container', '#item_no_container', '#item_joc_number_jo', '#item_barang_masuk_id_container', '#item_sisa_barang_container', '#item_status_ngepok');
            });

            // Fungsi untuk memanggil API berdasarkan pilihan
            function callApi(itemDropdownId, unitInputId, jocNumberInputId, barangMasukIdInputId, sisaBarangInputId) {
                const customerId = $('#customer_id').val();
                const warehouseId = $('#gudang_id').val();

                // Validasi input
                if (!customerId || !warehouseId) {
                    alert('Mohon pilih Pemilik Barang dan Gudang terlebih dahulu.');
                    return;
                }

                // Tentukan URL API berdasarkan nilai isContainer
                const url = isContainer ?
                    `/api/items/container/${customerId}/${warehouseId}` :
                    `/api/items/${customerId}/${warehouseId}`;

                // Lakukan AJAX request
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {

                        const itemsDropdown = $(itemDropdownId);
                        itemsDropdown.empty(); // Kosongkan dropdown sebelumnya
                        itemsDropdown.append('<option value="" disabled selected>Pilih Nama Barang</option>');

                        response.items.forEach(item => {
                            const displayText = isContainer ?
                                `${item.barang_name} || ${item.nomer_container}` :
                                `${item.barang_name} || ${item.joc_number}`;

                            itemsDropdown.append(
                                `<option value="${item.barang_id}" 
                            data-unit="${item.unit}" 
                            data-joc-number="${item.joc_number}" 
                            data-barang-masuk-id="${item.barang_masuk_id}" 
                            data-sisa-barang="${item.qty}"
                            data-status-ngepok="${item.status_ngepok}">
                        ${displayText}
                    </option>`
                            );
                        });

                        // Reset input terkait
                        $(unitInputId).val('').prop('readonly', true);
                        $(jocNumberInputId).val('Auto-generated');
                        $(barangMasukIdInputId).val('');
                        $(sisaBarangInputId).val('');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                        console.error('Response:', xhr.responseText);
                    },
                });
            }

            // Event untuk saat item dipilih
            $('#item_name_jo, #item_name_container').change(function() {
                const selectedOption = $(this).find('option:selected');

                const unit = selectedOption.data('unit');
                const jocNumber = selectedOption.data('joc-number');
                const barangMasukId = selectedOption.data('barang-masuk-id');
                const sisaBarang = selectedOption.data('sisa-barang');
                const transit = selectedOption.data('status-ngepok');

                // Tentukan input yang di-update berdasarkan modal aktif
                if (isContainer) {
                    $('#item_unit_container').val(unit).prop('readonly', true);
                    $('#item_no_container').val(jocNumber);
                    $('#item_barang_masuk_id_container').val(barangMasukId);
                    $('#item_sisa_barang_container').val(sisaBarang);
                    $('#item_status_ngepok').val(transit);
                } else {
                    $('#item_unit_jo').val(unit).prop('readonly', true);
                    $('#item_joc_number_jo').val(jocNumber);
                    $('#item_barang_masuk_id_jo').val(barangMasukId);
                    $('#item_sisa_barang_jo').val(sisaBarang);
                    $('#item_status_ngepok').val(transit);
                }

                validateQuantity();
            });

            $('#item_barang_masuk_id_jo').on('shown.bs.modal', function() {
                const itemQtyInput = document.getElementById('item_qty_jo');
                const itemSisaBarangInput = document.getElementById('item_sisa_barang_jo');
                const quantityWarningJO = document.getElementById('quantity-warning-jo');
                const quantityWarningStatusNgepok = document.getElementById('quantity-warning-jo-status-ngepok');
                const addItemButton = document.getElementById('add-item-btn-jo');
                const itemStatusNgepok = document.getElementById('item_status_ngepok');

                function checkQuantity() {
                    const qty = parseInt(itemQtyInput.value, 10) || 0;
                    const sisa = parseInt(itemSisaBarangInput.value, 10) || 0;
                    const itemStatusNgepokValue = itemStatusNgepok.value;
                    

                    if (itemStatusNgepokValue === "Transit") {
                        if (qty === sisa) {
                            quantityWarningStatusNgepok.style.display = 'none';
                            addItemButton.disabled = false;
                        }else{
                            quantityWarningStatusNgepok.style.display = 'block';
                            addItemButton.disabled = true;
                        }
                    
                    }else {
                        if (qty > sisa || qty <= 0) {
                        quantityWarningJO.style.display = 'block';
                        addItemButton.disabled = true;
                    } else {
                        quantityWarningJO.style.display = 'none';
                        addItemButton.disabled = false;
                    }
                    }
                }


                if (itemQtyInput) {
                    itemQtyInput.addEventListener('input', checkQuantity);
                }

                if (itemSisaBarangInput) {
                    itemSisaBarangInput.addEventListener('input', checkQuantity);
                }
            });

            $('#nomerContainerModal').on('shown.bs.modal', function() {
                const itemQtyInput = document.getElementById('item_qty_container');
                const itemSisaBarangInput = document.getElementById('item_sisa_barang_container');
                const quantityWarningContainer = document.getElementById('quantity-warning-container');
                const quantityWarningStatusNgepok = document.getElementById('quantity-warning-container-status-ngepok');
                const addItemButton = document.getElementById('add-item-btn-container');

                const itemStatusNgepok = document.getElementById('item_status_ngepok');

                function checkQuantity() {
                    const qty = parseInt(itemQtyInput.value, 10) || 0;
                    const sisa = parseInt(itemSisaBarangInput.value, 10) || 0;
                    const itemStatusNgepokValue = itemStatusNgepok.value;

                    if (itemStatusNgepokValue === "Transit") {
                        if (qty === sisa) {
                            quantityWarningStatusNgepok.style.display = 'none';
                            addItemButton.disabled = false;
                        }else{
                            quantityWarningStatusNgepok.style.display = 'block';
                            addItemButton.disabled = true;
                        }
                    
                    }else {
                        if (qty > sisa || qty <= 0) {
                        quantityWarningContainer.style.display = 'block';
                        addItemButton.disabled = true;
                    } else {
                        quantityWarningContainer.style.display = 'none';
                        addItemButton.disabled = false;
                    }
                    }
                }

                if (itemQtyInput) {
                    itemQtyInput.addEventListener('input', checkQuantity);
                    addItem();
                }

                if (itemSisaBarangInput) {
                    itemSisaBarangInput.addEventListener('input', checkQuantity);
                    addItem();
                }
            });

            $('#barang-keluar-form').submit(function() {
                $('#items-input').val(JSON.stringify(items));
            });

            window.editItem = function(index) {
                const item = items[index];

                $('#edit_item_joc_number_jo').val(item.no_ref);
                $('#edit_item_qty').val(item.qty);
                $('#edit_item_unit_jo').val(item.unit);
                $('#edit_item_sisa_barang').val(item.sisa_barang);
                $('#edit_item_barang_masuk_id_jo').val(item.barang_masuk_id);

                $('#edit_item_name').val(item.name)

                $('#save-edit-item-btn').data('index', index);

                $('#editItemModal').modal('show');
            };

            $('#save-edit-item-btn').click(function() {
                const index = $(this).data('index');
                const itemQty = parseFloat($('#edit_item_qty').val()) || 0;

                items[index].qty = itemQty;
                items[index].sisa_barang = $('#edit_item_sisa_barang').val();

                updateItemsTable();
                updateTotalQuantity();
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
            const alamatField = document.getElementById('alamatField');

            if (shippingOption === 'kirim') {
                mobilField.style.display = 'block';
                hargaKirimField.style.display = 'block';
                alamatField.style.display = 'block';
            } else if (shippingOption === 'takeout') {
                mobilField.style.display = 'block';
                hargaKirimField.style.display = 'none';
                alamatField.style.display = 'none';

                document.getElementById('display_harga_kirim_barang').value = '';
                document.getElementById('harga_kirim_barang').value = '';
                document.getElementById('address').value = '';
            } else {
                mobilField.style.display = 'none';
                hargaKirimField.style.display = 'none';
                alamatField.style.display = 'none';

                document.getElementById('display_harga_kirim_barang').value = '';
                document.getElementById('harga_kirim_barang').value = '';
                document.getElementById('address').value = '';
            }
        }

        window.onload = function() {
            toggleFieldsKirim();
        };


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

        let items = []; // Array global untuk menyimpan item

        function addItem() {
            const itemNoRef = document.getElementById('item_no_container').value || 'Auto-generated';
            const itemName = document.getElementById('item_name_container').value;
            const itemQty = parseInt(document.getElementById('item_qty_container').value, 10) || 0;
            const itemUnit = document.getElementById('item_unit_container').value;

            if (itemName && itemQty > 0) {
                // Tambahkan item ke array
                items.push({
                    no_ref: itemNoRef,
                    name: itemName,
                    qty: itemQty,
                    unit: itemUnit,
                });

                // Perbarui tabel
                updateItemsTable();

                // Tutup modal dan reset form
                $('#itemModalContainer').modal('hide');
                resetModalForm();
            }
        }

        function resetModalForm() {
            document.getElementById('item_name_container').value = '';
            document.getElementById('item_qty_container').value = '';
            document.getElementById('item_unit_container').value = '';
            document.getElementById('item_sisa_barang_container').value = '';
        }


        let quantityColumnIndex = 4; // Default untuk btn-nomer-container

        function updateTotalQuantity() {
            let totalQuantity = 0;

            $('#items-table tbody tr').each(function() {
                var itemQty = parseFloat($(this).find(`td:nth-child(${quantityColumnIndex})`).text()) || 0;
                totalQuantity += itemQty;
            });

            $('#totalQuantity').text(totalQuantity);
        }

        $('#btn-jo-number').on('click', function() {
            quantityColumnIndex = 3;
            updateTotalQuantity();
        });

        $('#btn-nomer-container').on('click', function() {
            quantityColumnIndex = 4;
            updateTotalQuantity();
        });

            document.getElementById('btn-jo-number').addEventListener('click', function() {
                if (this.disabled) {
                    alert('ANDA TELAH MEMILIH ADD ITEM WITH CONTAINER');
                } else {
                    document.getElementById('btn-nomer-container').disabled = true;
                }

            });

            // Ketika tombol Add Item Partai diklik
            document.getElementById('btn-nomer-container').addEventListener('click', function() {
                if (this.disabled) {
                    alert('ANDA TELAH MEMILIH ADD ITEM FIFO');
                } else {
                document.getElementById('btn-jo-number').disabled = true;

                }
            });

           

    </script>


</body>

</html>