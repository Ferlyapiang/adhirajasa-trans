<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Car Type</title>

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
        <x-sidebar />
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Master Data |</span> 
                                <span>Jenis Mobil</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Add Jenis Mobil</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('master-data.jenis-mobil.store') }}" method="POST"> <!-- Corrected route -->
                                    @csrf
                                    <div class="form-group">
                                        <label for="type">Type Mobil</label>
                                        <input type="text" id="type" name="type" class="form-control" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="rental_price">Harga Bongkar Muat</label>
                                        <input type="text" id="rental_price" name="rental_price" class="form-control" required oninput="formatRupiah(this)">
                                        <input type="hidden" id="rental_price_raw" name="rental_price"> <!-- Hidden input for raw numeric value -->
                                        @error('rental_price')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control" required>
                                            <option value="" disabled selected>Pilih Status</option>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="{{ route('master-data.jenis-mobil.index') }}" class="btn btn-secondary">Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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

    <script>
        function formatRupiah(input) {
            // Remove all non-numeric characters except for the decimal point
            let value = input.value.replace(/[^,\d]/g, '');
            
            // Convert the string to a number
            let number = parseFloat(value.replace(',', '.')) || 0;

            // Set the actual integer value to be submitted in the hidden field
            document.getElementById('rental_price_raw').value = number;

            // Format the number to Rupiah and update the input field for display
            input.value = new Intl.NumberFormat('id-ID', {
                style: 'decimal', // Keep as decimal
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(number);
        }
    </script>
</body>
</html>
