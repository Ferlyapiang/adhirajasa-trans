<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Barang Masuk</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
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

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12"
                            style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Data Gudang |</span>
                                <span>Barang Masuk</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h2>Tambah Barang Masuk</h2>
                <form id="barangMasukForm" action="{{ route('data-gudang.barang-masuk.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="joc_number">Nomer JO</label>
                        <input type="text" name="joc_number" id="joc_number" class="form-control"
                            placeholder="Masukan Nomer JO">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control"
                            placeholder="Select date" required>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang_id" id="gudang" class="form-control"
                            {{ $user->warehouse_id ? 'disabled' : '' }} required>
                            @if (is_null($user->warehouse_id))
                            <option value="" disabled selected>Pilih Gudang Penyimpanan</option>
                            @endif
                            @foreach ($gudangs as $gudang)
                            <option value="{{ $gudang->id }}"
                                {{ $user->warehouse_id == $gudang->id ? 'selected' : '' }}>
                                {{ $gudang->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="customer_id" id="nama_pemilik" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Pemilik Barang</option>
                            @foreach($pemilik as $owner)
                            @if(Auth::user()->warehouse_id === null)
                            <option value="{{ $owner->id }}">{{ $owner->name }} | {{ optional($owner->warehouse)->name }}</option>
                            @else
                            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type_mobil_id">Jenis Mobil</label>
                        <select name="type_mobil_id" id="type_mobil_id" class="form-control">
                            <option value="">Pilih Jenis Mobil</option>
                            @foreach ($typeMobilOptions as $typeMobil)
                            <option value="{{ $typeMobil->id }}">{{ $typeMobil->type }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="form-group">
                        <label for="selection" class="form-label">Choose Identification Type:</label>
                        <select id="id_selection" class="form-control" onchange="toggleFields()" required>
                            <option value="">-- Select --</option>
                            <option value="nomer_polisi">Nomer Polisi</option>
                            <option value="nomer_container">Nomer Container</option>
                        </select>
                    </div>

                    <div id="nomer_polisi_field" class="mb-3" style="display:none;">
                        <label for="nomer_polisi">Nomer Polisi</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control">
                    </div>

                    <div id="nomer_container_field" class="mb-3" style="display:none;"> <!-- Fixed id here -->
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="harga_simpan_barang">Harga Simpan Barang</label>
                        <input type="text" id="display_harga_simpan_barang" class="form-control" oninput="formatRupiah(this, 'harga_simpan_barang')">
                        <input type="hidden" name="harga_simpan_barang" id="harga_simpan_barang">
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

                    <div class="form-group">
                        <label for="status_ngepok">Apakah Ini Barang Transit ?</label>
                        <select id="status_ngepok" class="form-control" name="status_ngepok">
                            <option value="">Tidak</option>
                            <option value="Transit">Ya</option>
                        </select>
                    </div>

                    <h2>Items</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#itemModal">Add
                        Item</button>
                    <input type="hidden" name="items" id="items-input" value="[]">
                    <!-- Items Table -->
                    <table id="items-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="1" style="text-align:right;">Total:</th>
                                <th id="totalQuantity"></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                    </table>

                    <br><br>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('data-gudang.barang-masuk.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.footer')
        <!-- /.footer -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
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
                        <label for="item_name">Nama Barang</label>
                        <br>
                        <select id="item_name" class="form-control" required>
                            <!-- Options will be populated based on selected owner -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="item_qty">Quantity</label>
                        <input type="number" id="item_qty" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="item_unit">Unit</label>
                        <input type="text" id="item_unit" class="form-control" readonly required>
                    </div>

                    <div class="form-group">
                        <label for="item_notes">Notes</label>
                        <textarea id="item_notes" class="form-control" rows="4" required></textarea>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add-item-to-list">Add Item</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel"
        aria-hidden="true">
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
                        <label for="edit_item_name">Nama Barang</label>
                        <input type="text" id="edit_item_name" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="edit_item_qty">Quantity</label>
                        <input type="number" id="edit_item_qty" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_item_unit">Unit</label>
                        <input type="text" id="edit_item_unit" class="form-control" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="edit_item_notes">Notes</label>
                        <input type="text" id="edit_item_notes" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update-item">Update Item</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for dropdowns
            $('#gudang, #nama_pemilik, #item_name').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true
            });

            let editRow; // Store the row to be edited
            let itemsInTable = []; // Array to keep track of items added to the table

            // Function to update the hidden input field with the list of items
            function updateItemsInput() {
                let items = [];

                $('#items-table tbody tr').each(function() {
                    const itemId = $(this).data('id');
                    const itemQty = $(this).find('td:eq(1)').text();
                    const itemUnit = $(this).find('td:eq(2)').text();
                    const itemNotes = $(this).find('td:eq(3)').text();

                    items.push({
                        id: itemId,
                        quantity: itemQty,
                        unit: itemUnit,
                        notes: itemNotes
                    });
                });

                $('#items-input').val(JSON.stringify(items));
            }

            // Function to update the dropdown based on items in the table
            function updateItemDropdown() {
                $('#item_name option').each(function() {
                    const optionId = $(this).val();
                    if (itemsInTable.includes(optionId)) {
                        $(this).prop('disabled', true); // Disable option if it's already in the table
                    } else {
                        $(this).prop('disabled', false); // Enable option if it's not in the table
                    }
                });
            }

            // Call updateItemDropdown on page load to initialize the dropdown correctly
            updateItemDropdown();

            // Fetch items based on selected owner
            $('#nama_pemilik').change(function() {
                const pemilik = $(this).val();
                $.ajax({
                    url: "{{ route('data-gudang.items-by-owner') }}",
                    method: 'GET',
                    data: {
                        pemilik: pemilik
                    },
                    success: function(data) {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }

                        let options = '<option value="">Select Item</option>';
                        $.each(data, function(key, item) {
                            if (!itemsInTable.includes(item.id)) {
                                options += `<option value="${item.id}" data-jenis="${item.jenis}">${item.nama_barang}</option>`;
                            }
                        });
                        $('#item_name').html(options);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            });

            // Fetch unit for selected item
            $('#item_name').change(function() {
                const unit = $('#item_name option:selected').data('jenis');
                $('#item_unit').val(unit || '');
            });

            // Add item to the list
            $('#add-item-to-list').click(function() {
                const itemName = $('#item_name option:selected').text();
                const itemId = $('#item_name').val(); // Get the selected item's id
                const itemQty = $('#item_qty').val();
                const itemUnit = $('#item_unit').val();
                const itemNotes = $('#item_notes').val();

                if (itemName && itemQty && itemUnit) {
                    // Check if the item is already in the table
                    if (!itemsInTable.includes(itemId)) {
                        $('#items-table tbody').append(`
                        <tr data-id="${itemId}">
                            <td>${itemName}</td>
                            <td>${itemQty}</td>
                            <td>${itemUnit}</td>
                            <td>${itemNotes}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </td>
                        </tr>
                    `);

                        // Add the item ID to the itemsInTable array
                        itemsInTable.push(itemId);

                        // Clear the form fields
                        $('#item_name').val('').trigger('change'); // Clear select2
                        $('#item_qty').val('');
                        $('#item_unit').val('');
                        $('#item_notes').val('');

                        $('#itemModal').modal('hide');
                        updateItemsInput(); // Update hidden input when item is added

                        // Update the dropdown to remove added items
                        updateItemDropdown();

                        // Update total Quantity
                        updateTotalQuantity();
                    }
                    // If the item is already in the table, do not show an alert
                } else {
                    alert('Please fill in all fields');
                }
            });

            // Remove item from list
            $(document).on('click', '.remove-item', function() {
                const row = $(this).closest('tr');
                const itemId = row.data('id');

                // Remove the item ID from the itemsInTable array
                itemsInTable = itemsInTable.filter(id => id !== itemId);

                row.remove();

                updateItemsInput(); // Update hidden input when item is removed

                // Update the dropdown to show the removed item
                updateItemDropdown();

                // Update total Quantity
                updateTotalQuantity();
            });

            // Open edit modal
            $(document).on('click', '.edit-item', function() {
                editRow = $(this).closest('tr');
                const itemName = editRow.find('td:eq(0)').text(); // Get the item name
                const itemQty = editRow.find('td:eq(1)').text();
                const itemUnit = editRow.find('td:eq(2)').text();
                const itemNotes = editRow.find('td:eq(3)').text();
                $('#edit_item_name').val(itemName); // Set item name in readonly field
                $('#edit_item_qty').val(itemQty);
                $('#edit_item_unit').val(itemUnit);
                $('#edit_item_notes').val(itemNotes);

                $('#editItemModal').modal('show');
            });

            // Update item in the list
            $('#update-item').click(function() {
                const updatedQty = $('#edit_item_qty').val();
                const updatedUnit = $('#edit_item_unit').val();
                const updatedNotes = $('#edit_item_notes').val();

                if (updatedQty) {
                    editRow.find('td:eq(1)').text(updatedQty);
                    editRow.find('td:eq(2)').text(updatedUnit);
                    editRow.find('td:eq(3)').text(updatedNotes);
                    $('#editItemModal').modal('hide');
                    updateItemsInput(); // Update hidden input when item is edited
                    // Update total Quantity
                    updateTotalQuantity();
                } else {
                    alert('Please enter a valid quantity');
                }
            });

            // Form validation on submit
            $('#barangMasukForm').on('submit', function(event) {
                if ($('#items-table tbody tr').length === 0) {
                    event.preventDefault();
                    alert('Tolong masukan Items setidaknya 1 barang.');
                }
            });
        });
        // Toggle fields based on selection
        function toggleFields() {
            var selection = document.getElementById('id_selection').value;

            // Hide both fields initially
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

        // Toggle lembur section
        function toggleLembur() {
            let select = document.getElementById('ada_lembur');
            let lemburSection = document.getElementById('lembur_section');
            if (select.value === 'ya') {
                lemburSection.style.display = 'block'; // Show lembur field
            } else {
                lemburSection.style.display = 'none'; // Hide lembur field
            }
        }

        // Function to format input as Rupiah
        function formatRupiah(displayInput, hiddenInputId) {
            let angka = displayInput.value.replace(/[^,\d]/g, ''); // Only numbers
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
            displayInput.value = formatted.replace('IDR', 'Rp.');

            // Save the original numeric value to the hidden element
            document.getElementById(hiddenInputId).value = angka;
        }

        function updateTotalQuantity() {
            let totalQuantity = 0;

            // Loop melalui semua baris yang ada di tabel secara manual
            $('#items-table tbody tr').each(function() {
                var itemQty = parseFloat($(this).find('td:nth-child(2)').text()) || 0;
                totalQuantity += itemQty;
            });

            // Update total Quantity di footer
            $('#totalQuantity').text(totalQuantity);
        }
    </script>

</body>

</html>