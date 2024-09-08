<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Barang</title>
    <!-- Add your stylesheets and scripts here -->
    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
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
        @include('admin.sidebar')
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px;">Create Data Barang</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Create Barang</h1>
                <form action="{{ route('master-data.barang.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" id="nama_barang" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select name="jenis" class="form-control select2" id="jenis" required>
                            <option value="" disabled selected>Pilih Jenis Barang</option>
                            @foreach($itemTypes as $itemType)
                                <option value="{{ $itemType->name }}">{{ $itemType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sku">SKU</label>
                        <input type="text" name="sku" class="form-control" id="sku" required>
                    </div>
                    <div class="form-group">
                        <label for="pemilik">Pemilik</label>
                        <select name="pemilik" class="form-control select2" id="pemilik" required>
                            <option value="" disabled selected>Pilih Pemilik Barang</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control select2" id="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.footer')
        <!-- /.footer -->
    </div>

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 on select elements with placeholders
            $('#jenis, #pemilik').select2({
                placeholder: function(){
                    return $(this).data('placeholder');
                },
                allowClear: true
            });
        });
    </script>
</body>
</html>
