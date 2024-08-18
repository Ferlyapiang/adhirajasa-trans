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
            <div class="container">
                <h1>Tambah Barang Masuk</h1>
                <form action="{{ route('data-gudang.barang-masuk.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" placeholder="Select date" required>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang_id" id="gudang" class="form-control" required>
                            @foreach($gudangs as $gudang)
                                <option value="{{ $gudang->id }}">{{ $gudang->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="customer_id" id="nama_pemilik" class="form-control" required>
                            @foreach($pemilik as $owner)
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
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control">
                    </div>

                    <h2>Items</h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#itemModal">Add Item</button>

                    <div id="items-container">
                        <!-- Items will be added here dynamically -->
                    </div>

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
                        <input type="text" id="item_unit" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add-item-to-list">Add Item</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        // Fetch items based on selected owner
        $('#nama_pemilik').change(function () {
            const customerId = $(this).val();
            $.ajax({
                url: "{{ route('data-gudang.items-by-owner') }}",
                method: 'GET',
                data: { customer_id: customerId },
                success: function (data) {
                    let options = '<option value="">Select Item</option>';
                    $.each(data, function (key, item) {
                        options += `<option value="${item.id}">${item.nama_barang}</option>`;
                    });
                    $('#item_name').html(options);
                }
            });
        });

        // Add item to list
        $('#add-item-to-list').click(function () {
            const itemName = $('#item_name').val();
            const itemQty = $('#item_qty').val();
            const itemUnit = $('#item_unit').val();

            if (itemName && itemQty && itemUnit) {
                $('#items-container').append(`
                    <div class="item">
                        <div class="form-group">
                            <label for="item_name[]">Nama Barang</label>
                            <input type="text" class="form-control" value="${$('#item_name option:selected').text()}" readonly>
                            <input type="hidden" name="items[][barang_id]" value="${itemName}">
                        </div>

                        <div class="form-group">
                            <label for="item_qty[]">Quantity</label>
                            <input type="number" name="items[][qty]" class="form-control" value="${itemQty}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="item_unit[]">Unit</label>
                            <input type="text" name="items[][unit]" class="form-control" value="${itemUnit}" readonly>
                        </div>

                        <button type="button" class="btn btn-danger remove-item">Remove Item</button>
                    </div>
                `);

                $('#itemModal').modal('hide');
                $('#item_name').val('');
                $('#item_qty').val('');
                $('#item_unit').val('');
            }
        });

        // Remove item from list
        $('#items-container').on('click', '.remove-item', function () {
            $(this).parent().remove();
        });
    });
    </script>
</body>
</html>
