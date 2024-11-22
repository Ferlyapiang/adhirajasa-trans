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

        .readonly-select {
            pointer-events: none;
            background-color: #e9ecef;
            color: #6c757d;
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
                        <select name="gudang_id" id="gudang" class="form-control readonly-select" required>
                            <option value="" selected>Pilih Nama Gudang Penyimpanan</option>
                            @foreach ($gudangs as $gudang)
                            <option value="{{ $gudang->id }}"
                                {{ $gudang->id == $barangMasuk->gudang_id ? 'selected' : '' }}>{{ $gudang->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="customer_id" id="nama_pemilik" class="form-control readonly-select" required>
                            <option value="" selected>Pilih Nama Pemilik Barang</option>
                            @foreach ($pemilik as $owner)
                            <option value="{{ $owner->id }}"
                                {{ $owner->id == $barangMasuk->customer_id ? 'selected' : '' }}>{{ $owner->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type_mobil_id">Jenis Mobil</label>
                        <select name="type_mobil_id" id="type_mobil_id" class="form-control">
                            <option value="" hidden>Pilih Jenis Mobil</option>
                            @foreach ($typeMobilOptions as $typeMobil)
                            <option value="{{ $typeMobil->id }}" {{ $barangMasuk->type_mobil_id == $typeMobil->id ? 'selected' : '' }}>
                                {{ $typeMobil->type }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="selection" class="form-label">Choose Identification Type:</label>
                        <select id="id_selection" class="form-control" onchange="toggleFields()" required>
                            <option value="">-- Select --</option>
                            <option value="nomer_polisi" {{ $barangMasuk->nomer_polisi ? 'selected' : '' }}>Nomer Polisi</option>
                            <option value="nomer_container" {{ $barangMasuk->nomer_container ? 'selected' : '' }}>Nomer Container</option>
                        </select>
                    </div>

                    <div id="nomer_polisi_field" class="mb-3" style="display: none;">
                        <label for="nomer_polisi">Nomer Polisi</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control"
                            value="{{ $barangMasuk->nomer_polisi }}">
                    </div>

                    <div id="nomer_container_field" class="mb-3" style="display: none;">
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control"
                            value="{{ $barangMasuk->nomer_container }}">
                    </div>


                    <div class="form-group">
                        <label for="harga_simpan_barang">Harga Simpan Barang</label>
                        <input type="text" id="display_harga_simpan_barang" class="form-control"
                            value="{{ number_format($barangMasuk->harga_simpan_barang, 0, ',', '.') }}"
                            oninput="formatRupiah(this, 'harga_simpan_barang')">
                        <input type="hidden" name="harga_simpan_barang" id="harga_simpan_barang"
                            value="{{ $barangMasuk->harga_simpan_barang }}">
                    </div>

                    <div class="form-group">
                        <label for="harga_lembur">Harga Lembur</label>
                        <input type="text" id="display_harga_lembur" class="form-control"
                            value="{{ number_format($barangMasuk->harga_lembur, 0, ',', '.') }}"
                            oninput="formatRupiah(this, 'harga_lembur')">
                        <input type="hidden" name="harga_lembur" id="harga_lembur"
                            value="{{ $barangMasuk->harga_lembur }}">
                    </div>

                    <div class="form-group">
                        <label for="status_ngepok">Apakah Ini Barang Transit ?</label>
                        <select id="status_ngepok" class="form-control" name="status_ngepok">
                            <option value="" {{ $barangMasuk->status_ngepok ? 'selected' : '' }}>Tidak</option>
                            <option value="Transit" {{ $barangMasuk->status_ngepok ? 'selected' : '' }}>Ya</option>
                        </select>
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
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangMasuk->items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td>{{ $item->barang->nama_barang ?? 'Unknown' }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->notes }}</td>
                                <td style="display: none">{{ $item->barang_id }}</td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="1" style="text-align: right;">Total Quantity:</th>
                                <th id="totalQuantity">0</th>
                                <th colspan="3"></th> <!-- Untuk menyelaraskan kolom -->
                            </tr>
                        </tfoot>
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
                    <div class="form-group">
                        <label for="item_notes">Notes</label>
                        <textarea type="text" id="item_notes" class="form-control" required></textarea>
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
                        <input type="text" id="edit_item_name" class="form-control" readonly required>
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
                        <textarea type="text" id="edit_item_notes" class="form-control" required></textarea>
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
            let editRow;
            let itemsInTable = [];
            let barangIdsInTable = [];

            // Populate itemsInTable and barangIdsInTable from existing table rows
            $('#items-table tbody tr').each(function() {
                const itemId = parseInt($(this).data('id'));
                const barangId = $(this).find('td').eq(4).text().trim(); // Adjusting index to match your structure
                if (!isNaN(itemId)) {
                    itemsInTable.push(itemId);
                    updateTotalQuantity();
                }
                if (barangId) {
                    barangIdsInTable.push(barangId);
                    updateTotalQuantity();
                }
            });

            // Function to fetch items based on owner
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
                            // Only add items that are not already in the table
                            if (!barangIdsInTable.includes(item.id)) {
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

            // Initial fetch of items
            fetchItemsForOwner($('#nama_pemilik').val());

            // Prevent form submission if no items are in the table
            $('#barangMasukForm').on('submit', function(event) {
                if ($('#items-table tbody tr').length === 0) {
                    event.preventDefault();
                    alert('Tolong masukan Items setidaknya 1 barang.');
                }
            });

            // Fetch new items when the owner changes
            $('#nama_pemilik').change(function() {
                fetchItemsForOwner($(this).val());
            });

            // Set the unit field based on selected item
            $('#item_name').change(function() {
                $('#item_unit').val($('#item_name option:selected').data('jenis') || '');
            });

            // Add item to the list
            $('#add-item-to-list').click(function() {
                const id = parseInt($('#item_name').val());
                const name = $('#item_name option:selected').data('nama');
                const qty = $('#item_qty').val();
                const unit = $('#item_unit').val();
                const notes = $('#item_notes').val();
                const barangId = $('#item_name option:selected').val();

                if (barangIdsInTable.includes(barangId)) {
                    alert('This item is already in the table.');
                    return;
                }

                if (id && qty && unit) {
                    const newItem = {
                        id: id,
                        nama_barang: name,
                        quantity: qty,
                        unit: unit,
                        notes: notes
                    };

                    $('#items-table tbody').append(`
                <tr data-id="${newItem.id}">
                    <td>${newItem.nama_barang}</td>
                    <td>${newItem.quantity}</td>
                    <td>${newItem.unit}</td>
                    <td>${newItem.notes}</td>
                    <td style="display: none">${barangId}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                    </td>
                </tr>
            `);

                    itemsInTable.push(newItem.id);
                    barangIdsInTable.push(barangId);
                    updateItemsInput();

                    updateTotalQuantity();
                    $('#itemModal').modal('hide');

                    // Clear input fields
                    $('#item_name').val('');
                    $('#item_qty').val('');
                    $('#item_unit').val('');
                    $('#item_notes').val('');

                    // Remove the item from the select options
                    removeOptionFromSelect(barangId);
                } else {
                    alert('Please fill in all the fields.');
                }
            });

            // Remove item from the list
            $(document).on('click', '.remove-item', function() {
                const row = $(this).closest('tr');
                const itemId = parseInt(row.data('id'));
                const barangId = row.find('td').eq(4).text().trim(); // Updated index

                itemsInTable = itemsInTable.filter(id => id !== itemId);
                barangIdsInTable = barangIdsInTable.filter(id => id !== barangId);
                row.remove();
                updateItemsInput();

                updateTotalQuantity();

                // Add the item back to the select options
                addOptionToSelect(barangId);
            });

            // Edit item in the list
            $(document).on('click', '.edit-item', function() {
                editRow = $(this).closest('tr');
                $('#edit_item_name').val(editRow.find('td').eq(0).text());
                $('#edit_item_qty').val(editRow.find('td').eq(1).text());
                $('#edit_item_unit').val(editRow.find('td').eq(2).text());
                $('#edit_item_notes').val(editRow.find('td').eq(3).text());
                $('#update-item').data('id', editRow.data('id'));
                $('#editItemModal').modal('show');
            });

            // Update edited item
            $('#update-item').click(function() {
                const id = $(this).data('id');
                const row = $('#items-table tbody').find(`tr[data-id="${id}"]`);
                const barangId = row.find('td').eq(4).text().trim(); // Updated index

                row.find('td').eq(0).text($('#edit_item_name').val());
                row.find('td').eq(1).text($('#edit_item_qty').val());
                row.find('td').eq(2).text($('#edit_item_unit').val());
                row.find('td').eq(3).text($('#edit_item_notes').val());

                updateItemsInput();

                updateTotalQuantity();
                $('#editItemModal').modal('hide');
            });

            // Function to remove an option from the select element
            function removeOptionFromSelect(barangId) {
                $(`#item_name option[value="${barangId}"]`).remove();
            }

            // Function to add an option back to the select element
            function addOptionToSelect(barangId) {
                // Assuming you have a data source to get the item details
                // Replace with the actual method to retrieve item name
                // const itemName = `Item Name for ${barangId}`; // Fetch item name based on ID or other data
                $('#item_name').append(new Option(itemName, barangId));
            }

            function updateItemsInput() {
                const items = [];
                $('#items-table tbody tr').each(function() {
                    var id = $(this).data('id');
                    var name = $(this).find('td').eq(0).text();
                    var qty = $(this).find('td').eq(1).text();
                    var unit = $(this).find('td').eq(2).text();
                    var notes = $(this).find('td').eq(3).text();
                    var barang_id = $(this).find('td').eq(4).text();

                    items.push({
                        id: id,
                        nama_barang: barang_id,
                        quantity: qty,
                        unit: unit,
                        notes: notes
                    });
                });
                $('#items-input').val(JSON.stringify(items));
            }

        });

        function toggleFields() {
            var selection = document.getElementById('id_selection').value;

            // Hide both fields first
            document.getElementById('nomer_polisi_field').style.display = 'none';
            document.getElementById('nomer_container_field').style.display = 'none';

            // Clear the values when switching options
            if (selection === 'nomer_polisi') {
                document.getElementById('nomer_container').value = ''; // Clear container field
                document.getElementById('nomer_polisi_field').style.display = 'block'; // Show polisi field
            } else if (selection === 'nomer_container') {
                document.getElementById('nomer_polisi').value = ''; // Clear polisi field
                document.getElementById('nomer_container_field').style.display = 'block'; // Show container field
            }
        }

        // Panggil toggleFields saat halaman pertama kali dimuat
        window.onload = function() {
            toggleFields();
        };

        function toggleLembur() {
            let select = document.getElementById('ada_lembur');
            let lemburSection = document.getElementById('lembur_section');
            if (select.value === 'ya') {
                lemburSection.style.display = 'block'; // Tampilkan field harga lembur
            } else {
                lemburSection.style.display = 'none'; // Sembunyikan field harga lembur
            }
        }

        // Function to format currency
        function formatRupiah(displayInput, hiddenInputId) {
            let angka = displayInput.value.replace(/[^,\d]/g, '');
            let formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
            displayInput.value = formatted.replace('IDR', '').trim();
            document.getElementById(hiddenInputId).value = angka;
        }

        function updateTotalQuantity() {
            let totalQuantity = 0;

            // Loop melalui semua baris tabel dan hitung total quantity
            $('#items-table tbody tr').each(function() {
                const itemQty = parseFloat($(this).find('td:nth-child(2)').text()) || 0; // Ambil nilai quantity dari kolom kedua
                totalQuantity += itemQty; // Tambahkan ke total quantity
            });

            // Update total quantity di footer
            $('#totalQuantity').text(totalQuantity);
        }
    </script>


</body>

</html>