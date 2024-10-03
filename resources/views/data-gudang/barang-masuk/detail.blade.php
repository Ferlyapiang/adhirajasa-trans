<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Barang Masuk</title>
    

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
                <h2>Detail Barang Masuk {{ $barangMasuk->joc_number }}</h2>
                <form id="barangMasukForm" action="{{ route('data-gudang.barang-masuk.update', $barangMasuk->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control"
                            value="{{ $barangMasuk->tanggal_masuk }}" placeholder="Select date" readonly>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang_id" id="gudang" class="form-control" required disabled>
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
                        <select name="customer_id" id="nama_pemilik" class="form-control" required disabled>
                            <option value="" disabled selected>Pilih Nama Pemilik Barang</option>
                            @foreach ($pemilik as $owner)
                                <option value="{{ $owner->id }}"
                                    {{ $owner->id == $barangMasuk->customer_id ? 'selected' : '' }}>{{ $owner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type_mobil_id">Jenis Mobil</label>
                        <select name="type_mobil_id" id="type_mobil_id" class="form-control" disabled>
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
                        <select id="id_selection" class="form-select" onchange="toggleFields()" required>
                            <option value="">-- Select --</option>
                            <option value="nomer_polisi" {{ $barangMasuk->nomer_polisi ? 'selected' : '' }}>Nomer Polisi</option>
                            <option value="nomer_container" {{ $barangMasuk->nomer_container ? 'selected' : '' }}>Nomer Container</option>
                        </select>
                    </div>

                    <div id="nomer_polisi_field" class="mb-3">
                        <label for="nomer_polisi">Nomer Polisi</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control" 
                            value="{{ $barangMasuk->nomer_polisi }}" disabled>
                    </div>

                    <div id="nomer_container_field" class="mb-3">
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control" 
                            value="{{ $barangMasuk->nomer_container }}" disabled>
                    </div>


                    <div class="form-group">
                        <label for="harga_simpan_barang">Harga Simpan Barang</label>
                        <input type="text" id="display_harga_simpan_barang" class="form-control" 
                            value="{{ number_format($barangMasuk->harga_simpan_barang, 0, ',', '.') }}" 
                            oninput="formatRupiah(this, 'harga_simpan_barang')" disabled>
                        <input type="hidden" name="harga_simpan_barang" id="harga_simpan_barang" 
                            value="{{ $barangMasuk->harga_simpan_barang }}">
                    </div>

                    <div class="form-group">
                        <label for="harga_lembur">Harga Lembur</label>
                        <input type="text" id="display_harga_lembur" class="form-control" 
                            value="{{ number_format($barangMasuk->harga_lembur, 0, ',', '.') }}" 
                            oninput="formatRupiah(this, 'harga_lembur')" disabled>
                        <input type="hidden" name="harga_lembur" id="harga_lembur" 
                            value="{{ $barangMasuk->harga_lembur }}">
                    </div>

                    <h2>Items</h2>
                
                    <!-- Items Table -->
                    <table id="items-table">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barangMasuk->items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td>{{ $item->barang->nama_barang ?? 'Unknown' }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->unit }}</td>
                                    <td style="display: none">{{ $item->barang_id }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <br><br>
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

                    <input type="hidden" id="edit_item_id">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#item_name').on('change', function () {
                const itemId = $(this).val();
                $.get(`/items/${itemId}`, function (data) {
                    $('#item_unit').val(data.unit);
                });
            });

            $('#add-item-to-list').on('click', function () {
                const itemName = $('#item_name option:selected').text();
                const itemQty = $('#item_qty').val();
                const itemUnit = $('#item_unit').val();

                if (itemName && itemQty && itemUnit) {
                    const newRow = `<tr>
                        <td>${itemName}</td>
                        <td>${itemQty}</td>
                        <td>${itemUnit}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                        </td>
                    </tr>`;

                    $('#items-table tbody').append(newRow);
                    $('#itemModal').modal('hide');
                }
            });

            $(document).on('click', '.edit-item', function () {
                const row = $(this).closest('tr');
                const itemName = row.find('td').eq(0).text();
                const itemQty = row.find('td').eq(1).text();
                const itemUnit = row.find('td').eq(2).text();
                const itemId = row.data('id');

                $('#edit_item_name').val(itemName);
                $('#edit_item_qty').val(itemQty);
                $('#edit_item_unit').val(itemUnit);
                $('#edit_item_id').val(itemId);

                $('#editItemModal').modal('show');
            });

            $('#update-item').on('click', function () {
                const itemId = $('#edit_item_id').val();
                const itemQty = $('#edit_item_qty').val();

                const row = $(`#items-table tbody tr[data-id="${itemId}"]`);
                row.find('td').eq(1).text(itemQty);

                $('#editItemModal').modal('hide');
            });

            $(document).on('click', '.remove-item', function () {
                $(this).closest('tr').remove();
            });
        });

        function toggleFields() {
            var selection = document.getElementById('id_selection').value;

            document.getElementById('nomer_polisi_field').style.display = 'none';
            document.getElementById('nomer_container_field').style.display = 'none';

            if (selection === 'nomer_polisi') {
                document.getElementById('nomer_polisi_field').style.display = 'block';
            } else if (selection === 'nomer_container') {
                document.getElementById('nomer_container_field').style.display = 'block';
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            var nomerPolisi = "{{ $barangMasuk->nomer_polisi }}";
            var nomerContainer = "{{ $barangMasuk->nomer_container }}";
            var idSelection = document.getElementById('id_selection');

            idSelection.value = '';
            if (nomerPolisi) {
                idSelection.value = 'nomer_polisi';
                document.getElementById('nomer_polisi_field').style.display = 'block';
                document.getElementById('nomer_container_field').style.display = 'none';
            } else if (nomerContainer) {
                idSelection.value = 'nomer_container';
                document.getElementById('nomer_container_field').style.display = 'block';
                document.getElementById('nomer_polisi_field').style.display = 'none'; // Pastikan field lainnya disembunyikan
            } else {
                document.getElementById('nomer_polisi_field').style.display = 'none';
                document.getElementById('nomer_container_field').style.display = 'none';
            }
        });


    </script>
</body>

</html>
