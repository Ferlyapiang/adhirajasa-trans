<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Barang Masuk</title>

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
                <h2>Edit Barang Masuk {{ $barangMasuk->joc_number }}</h2>
                <form id="barangMasukForm" action="{{ route('data-gudang.barang-masuk.update', $barangMasuk->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control"
                            value="{{ $barangMasuk->tanggal_masuk }}" placeholder="Select date" required>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang_id" id="gudang" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Gudang Penyimpanan</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}"
                                    {{ $gudang->id == $barangMasuk->gudang_id ? 'selected' : '' }}>{{ $gudang->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="customer_id" id="nama_pemilik" class="form-control" required>
                            <option value="" disabled selected>Pilih Nama Pemilik Barang</option>
                            @foreach ($pemilik as $owner)
                                <option value="{{ $owner->id }}"
                                    {{ $owner->id == $barangMasuk->customer_id ? 'selected' : '' }}>{{ $owner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_mobil">Jenis Mobil (Optional)</label>
                        <input type="text" name="jenis_mobil" id="jenis_mobil" class="form-control"
                            value="{{ $barangMasuk->jenis_mobil }}">
                    </div>

                    <div class="form-group">
                        <label for="nomer_polisi">Nomer Polisi (Optional)</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control"
                            value="{{ $barangMasuk->nomer_polisi }}">
                    </div>

                    <div class="form-group">
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control"
                            value="{{ $barangMasuk->nomer_container }}" required>
                    </div>

                    <h2>Items</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#itemModal">Add
                        Item</button>
                    <input type="hidden" name="items" id="items-input"
                        value="{{ json_encode($barangMasuk->items) }}">
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
                            @foreach ($barangMasuk->items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $item->barang->nama_barang ?? 'Unknown' }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td style="display: none">{{ $item->barang_id }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br><br>
                    <button type="submit" class="btn btn-primary">Update</button>
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
                        <input type="text" id="edit_item_name" class="form-control" required>
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
            let barangIdsInTable = []; // Array to keep track of barang_id from the table

            // Initialize itemsInTable and barangIdsInTable with existing items from the table
            $('#items-table tbody tr').each(function() {
                const itemId = parseInt($(this).data('id'));
                const barangId = $(this).find('td').eq(3).text().trim(); // Get the hidden barang_id
                if (!isNaN(itemId)) {
                    itemsInTable.push(itemId);
                }
                if (barangId) {
                    barangIdsInTable.push(barangId);
                }
            });

            function fetchItemsForOwner(ownerId) {
                $.ajax({
                    url: "{{ route('data-gudang.items-by-owner') }}",
                    method: 'GET',
                    data: {
                        pemilik: ownerId
                    },
                    success: function(data) {
                        let options = '<option value="" disabled selected>Select Item</option>';
                        $.each(data, function(key, item) {
                            if (!itemsInTable.includes(item.id)) {
                                options +=
                                    `<option value="${item.id}" data-nama="${item.nama_barang}" data-jenis="${item.jenis}">${item.nama_barang}</option>`;
                            }
                        });
                        $('#item_name').html(options);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            fetchItemsForOwner($('#nama_pemilik').val());

            $('#barangMasukForm').on('submit', function(event) {
                if ($('#items-table tbody tr').length === 0) {
                    event.preventDefault();
                    alert('Tolong masukan Items setidaknya 1 barang.');
                }
            });

            $('#nama_pemilik').change(function() {
                fetchItemsForOwner($(this).val());
            });

            $('#item_name').change(function() {
                $('#item_unit').val($('#item_name option:selected').data('jenis') || '');
            });

            $('#add-item-to-list').click(function() {
                const id = parseInt($('#item_name').val());
                const name = $('#item_name option:selected').data('nama');
                const qty = $('#item_qty').val();
                const unit = $('#item_unit').val();
                const barangId = $('#item_name option:selected').val(); // Assume this is barang_id

                // Check for duplicate item in the table
                if (barangIdsInTable.includes(barangId)) {
                    alert('This item is already in the table.');
                    return; // Stop the function here if duplicate is found
                }

                if (id && qty && unit) {
                    const newItem = {
                        id: id,
                        nama_barang: name,
                        quantity: qty,
                        unit: unit
                    };

                    $('#items-table tbody').append(`
                            <tr data-id="${newItem.id}">
                                <td>${newItem.nama_barang}</td>
                                <td>${newItem.quantity}</td>
                                <td>${newItem.unit}</td>
                                <td style="display: none">${barangId}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                </td>
                            </tr>
                        `);

                    itemsInTable.push(newItem.id); // Add the new item id to the list
                    barangIdsInTable.push(barangId); // Add the new barang_id to the list
                    updateItemsInput(); // Update hidden input field
                    $('#itemModal').modal('hide'); // Close the modal

                    // Clear the input fields
                    $('#item_name').val('');
                    $('#item_qty').val('');
                    $('#item_unit').val('');
                } else {
                    alert('Please fill in all the fields.');
                }
            });

            $(document).on('click', '.remove-item', function() {
                const row = $(this).closest('tr');
                const itemId = parseInt(row.data('id'));
                const barangId = row.find('td').eq(3).text().trim(); // Get the hidden barang_id

                itemsInTable = itemsInTable.filter(id => id !== itemId); // Remove the item id from the list
                barangIdsInTable = barangIdsInTable.filter(id => id !==
                barangId); // Remove the barang_id from the list
                row.remove(); // Remove the row from the table
                updateItemsInput(); // Update hidden input field
            });

            $(document).on('click', '.edit-item', function() {
                editRow = $(this).closest('tr');
                $('#edit_item_name').val(editRow.find('td').eq(0).text());
                $('#edit_item_qty').val(editRow.find('td').eq(1).text());
                $('#edit_item_unit').val(editRow.find('td').eq(2).text());
                $('#update-item').data('id', editRow.data('id'));
                $('#editItemModal').modal('show');
            });

            $('#update-item').click(function() {
                const id = $(this).data('id');
                const row = $('#items-table tbody').find(`tr[data-id="${id}"]`);
                const barangId = row.find('td').eq(3).text().trim(); // Get the hidden barang_id

                row.find('td').eq(0).text($('#edit_item_name').val());
                row.find('td').eq(1).text($('#edit_item_qty').val());
                row.find('td').eq(2).text($('#edit_item_unit').val());

                updateItemsInput(); // Update hidden input field
                $('#editItemModal').modal('hide'); // Close the modal
            });

            function updateItemsInput() {
                const items = [];
                $('#items-table tbody tr').each(function() {
                    var id = $(this).data('id');
                    var name = $(this).find('td').eq(0).text();
                    var qty = $(this).find('td').eq(1).text();
                    var unit = $(this).find('td').eq(2).text();
                    var barang_id = $(this).find('td').eq(3).text(); // Get the hidden barang_id

                    items.push({
                        id: id,
                        // barang_id: barang_id, // Store barang_id
                        nama_barang: barang_id,
                        quantity: qty,
                        unit: unit
                    });
                });
                $('#items-input').val(JSON.stringify(items));
            }
        });
    </script>

</body>

</html>
