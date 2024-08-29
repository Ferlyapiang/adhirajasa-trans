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
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
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
                <h1>Edit Barang Masuk</h1>
                <form id="barangMasukForm" action="{{ route('data-gudang.barang-masuk.update', $barangMasuk->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ $barangMasuk->tanggal_masuk }}" placeholder="Select date" required>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang_id" id="gudang" class="form-control" required>
                            @foreach($gudangs as $gudang)
                                <option value="{{ $gudang->id }}" {{ $gudang->id == $barangMasuk->gudang_id ? 'selected' : '' }}>{{ $gudang->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="customer_id" id="nama_pemilik" class="form-control" required>
                            @foreach($pemilik as $owner)
                                <option value="{{ $owner->id }}" {{ $owner->id == $barangMasuk->customer_id ? 'selected' : '' }}>{{ $owner->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_mobil">Jenis Mobil (Optional)</label>
                        <input type="text" name="jenis_mobil" id="jenis_mobil" class="form-control" value="{{ $barangMasuk->jenis_mobil }}">
                    </div>

                    <div class="form-group">
                        <label for="nomer_polisi">Nomer Polisi (Optional)</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control" value="{{ $barangMasuk->nomer_polisi }}">
                    </div>

                    <div class="form-group">
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control" value="{{ $barangMasuk->nomer_container }}">
                    </div>

                    <h2>Items</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#itemModal">Add Item</button>
                    <input type="hidden" name="items" id="items-input" value="{{ json_encode($barangMasuk->items) }}">
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
                            @foreach($barangMasuk->items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td>{{ $item->barang->nama_barang ?? 'Unknown'  }}</td>
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
        $(document).ready(function () {
            let editRow; // Store the row to be edited
            let itemsInTable = []; // Array to keep track of items added to the table
            const pemilik = $('#nama_pemilik').val();
        
            function fetchItemsForOwner(ownerId) {
                $.ajax({
                    url: "{{ route('data-gudang.items-by-owner') }}",
                    method: 'GET',
                    data: { pemilik: ownerId },
                    success: function(data) {
                        let options = '<option value="">Select Item</option>';
                        $.each(data, function(key, item) {
                            if (!itemsInTable.includes(item.id)) {
                                options += `<option value="${item.id}" data-nama="${item.nama_barang}" data-jenis="${item.jenis}">${item.nama_barang}</option>`;
                            }
                        });
                        $('#item_name').html(options);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }
        
            fetchItemsForOwner(pemilik);
        
            $('#nama_pemilik').change(function() {
                fetchItemsForOwner($(this).val());
            });
        
            $('#item_name').change(function() {
                $('#item_unit').val($('#item_name option:selected').data('jenis') || '');
            });
        
            $('#add-item-to-list').click(function () {
                var id = $('#item_name').val();
                var name = $('#item_name option:selected').data('nama');
                var qty = $('#item_qty').val();
                var unit = $('#item_unit').val();
        
                if (id && qty && unit) {
                    var newItem = { id: id, nama_barang: name, quantity: qty, unit: unit };
        
                    $('#items-table tbody').append(`
                        <tr data-id="${newItem.id}">
                            <td>${newItem.nama_barang}</td>
                            <td>${newItem.quantity}</td>
                            <td>${newItem.unit}</td>
                            <td style="display: none">${newItem.id}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </td>
                        </tr>
                    `);
        
                    itemsInTable.push(parseInt(newItem.id));
                    updateItemsInput();
                    $('#itemModal').modal('hide');
                }
            });
        
            $(document).on('click', '.remove-item', function () {
                var row = $(this).closest('tr');
                itemsInTable = itemsInTable.filter(itemId => itemId !== parseInt(row.data('id')));
                row.remove();
                updateItemsInput();
            });
        
            $(document).on('click', '.edit-item', function () {
                var row = $(this).closest('tr');
                $('#edit_item_name').val(row.find('td').eq(0).text());
                $('#edit_item_qty').val(row.find('td').eq(1).text());
                $('#edit_item_unit').val(row.find('td').eq(2).text());
                $('#update-item').data('id', row.data('id'));
                $('#editItemModal').modal('show');
            });
        
            $('#update-item').click(function () {
                var id = $(this).data('id');
                var row = $('#items-table tbody').find(`tr[data-id="${id}"]`);
                row.find('td').eq(0).text($('#edit_item_name').val());
                row.find('td').eq(1).text($('#edit_item_qty').val());
                row.find('td').eq(2).text($('#edit_item_unit').val());
                updateItemsInput();
                $('#editItemModal').modal('hide');
            });
        
            function updateItemsInput() {
                var items = [];
                $('#items-table tbody tr').each(function () {
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
