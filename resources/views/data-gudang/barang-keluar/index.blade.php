<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar List</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Custom CSS -->
    <style>
        .form-group {
            margin-bottom: 1rem;
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

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Barang Keluar List</h1>
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
                                    <h3 class="card-title">List of Barang Keluar</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('data-gudang.barang-keluar.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add New
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table id="barang-keluar-table" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Gudang</th>
                                                <th>Pemilik Barang</th>
                                                <th>Nomer Container</th>
                                                <th>Nomer Polisi</th>
                                                <th>Bank Transfer</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barangKeluars as $index => $barangKeluar)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $barangKeluar->tanggal_keluar->format('Y-m-d') }}</td>
                                                    <td>{{ $barangKeluar->gudang->name }}</td>
                                                    <td>{{ $barangKeluar->customer->name }}</td>
                                                    <td>{{ $barangKeluar->nomer_container }}</td>
                                                    <td>{{ $barangKeluar->nomer_polisi }}</td>
                                                    <td>{{ $barangKeluar->bankTransfer->bank_name }} - {{ $barangKeluar->bankTransfer->account_number }}</td>
                                                    <td>
                                                        <a href="{{ route('data-gudang.barang-keluar.edit', $barangKeluar->id) }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('data-gudang.barang-keluar.destroy', $barangKeluar->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Page-specific script -->
    <script>
        $(function() {
            $('#barang-keluar-table').DataTable({
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
            });
        });
    </script>
</body>

</html>
