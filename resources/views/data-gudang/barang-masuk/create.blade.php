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
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control"
                            placeholder="Select date" required>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang_id" id="gudang" class="form-control" required>
                            <option value="" disabled selected>Pilih Gudang Penyimpanan</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}">{{ $gudang->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="customer_id" id="nama_pemilik" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Pemilik Barang</option>
                            @foreach ($pemilik as $owner)
                                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_mobil">Jenis Mobil (Optional)</label>
                        <input type="text" name="jenis_mobil" id="jenis_mobil" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nomer_polisi">Nomer Polisi (Optional)</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control" required>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Items will be added here dynamically -->
                        </tbody>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="update-item">Update Item</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            let editRow; // Store the row to be edited
            let itemsInTable = []; // Array to keep track of items added to the table

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
                                options +=
                                    `<option value="${item.id}" data-jenis="${item.jenis}">${item.nama_barang}</option>`;
                            }
                        });
                        $('#item_name').html(options);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            });

            $('#barangMasukForm').on('submit', function(event) {
                if ($('#items-table tbody tr').length === 0) {
                    event.preventDefault();
                    alert('Tolong masukan Items setidaknya 1 barang.');
                }
            });

            // Fetch unit for selected item
            $('#item_name').change(function() {
                const unit = $('#item_name option:selected').data('jenis');
                $('#item_unit').val(unit || '');
            });

            // Add item to list
            $('#add-item-to-list').click(function() {
                const itemName = $('#item_name option:selected').text();
                const itemId = $('#item_name').val(); // Get the selected item's id
                const itemQty = $('#item_qty').val();
                const itemUnit = $('#item_unit').val();

                if (itemName && itemQty && itemUnit) {
                    // Check if the item is already in the table
                    if (!itemsInTable.includes(itemId)) {
                        $('#items-table tbody').append(`
                    <tr data-id="${itemId}">
                        <td>${itemName}</td>
                        <td>${itemQty}</td>
                        <td>${itemUnit}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                        </td>
                    </tr>
                `);

                        // Add the item ID to the itemsInTable array
                        itemsInTable.push(itemId);

                        // Clear the form fields
                        $('#item_name').val('');
                        $('#item_qty').val('');
                        $('#item_unit').val('');

                        $('#itemModal').modal('hide');
                        updateItemsInput(); // Update hidden input when item is added
                    } else {
                        alert('This item is already in the list');
                    }
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
            });

            // Open edit modal
            $(document).on('click', '.edit-item', function() {
                editRow = $(this).closest('tr');
                const itemName = editRow.find('td:eq(0)').text(); // Get the item name
                const itemQty = editRow.find('td:eq(1)').text();
                const itemUnit = editRow.find('td:eq(2)').text();

                $('#edit_item_name').val(itemName); // Set item name in readonly field
                $('#edit_item_qty').val(itemQty);
                $('#edit_item_unit').val(itemUnit);

                $('#editItemModal').modal('show');
            });

            // Update item in list
            $('#update-item').click(function() {
                const updatedQty = $('#edit_item_qty').val();
                const updatedUnit = $('#edit_item_unit').val();

                if (updatedQty) {
                    editRow.find('td:eq(1)').text(updatedQty);
                    editRow.find('td:eq(2)').text(updatedUnit);
                    $('#editItemModal').modal('hide');
                    updateItemsInput(); // Update hidden input when item is edited
                } else {
                    alert('Please enter a valid quantity');
                }
            });

            // Function to update the hidden input field with the list of items
            function updateItemsInput() {
                let items = [];

                $('#items-table tbody tr').each(function() {
                    const itemId = $(this).data('id');
                    const itemQty = $(this).find('td:eq(1)').text();
                    const itemUnit = $(this).find('td:eq(2)').text();

                    items.push({
                        id: itemId,
                        quantity: itemQty,
                        unit: itemUnit
                    });
                });

                $('#items-input').val(JSON.stringify(items));
            }
        });
    </script>
</body>

</html>
