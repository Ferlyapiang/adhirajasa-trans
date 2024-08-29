<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Barang Masuk</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

    <!-- Custom CSS -->
    <style>
        /* Custom styling for the DataTable */
        #barangMasukTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
            display: block;  /* Makes the table scrollable */
            overflow-x: auto;  /* Enables horizontal scrolling */
            white-space: nowrap;
        }

        #barangMasukTable thead {
            background-color: #f8f9fa;
        }

        #barangMasukTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #barangMasukTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        /* Custom modal styling */
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
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
                            <h1 class="m-0">Data Barang Masuk</h1>
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
                                    <h3 class="card-title">Daftar Barang Masuk</h3>
                                    <a href="{{ route('data-gudang.barang-masuk.create') }}" class="btn btn-primary float-right">Tambah Barang Masuk</a>
                                </div>
                                <div class="card-body">
                                    <table id="barangMasukTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Masuk</th>
                                                <th>No Ref (JOC)</th>
                                                <th>Jenis Mobil</th>
                                                <th>Nomer Polisi</th>
                                                <th>Nomer Container</th>
                                                <th>FIFO (IN)</th>
                                                <th>FIFO (OUT)</th>
                                                <th>FIFO (Sisa)</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($barangMasuks as $barangMasuk)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $barangMasuk->tanggal_masuk }}</td>
                                                {{-- <td>{{ $barangMasuk->joc_number }}</td> --}}
                                                <td>
                                                    <a href="{{ route('data-gudang.barang-masuk.detail', $barangMasuk->id) }}">
                                                        {{ $barangMasuk->joc_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $barangMasuk->jenis_mobil }}</td>
                                                <td>{{ $barangMasuk->nomer_polisi }}</td>
                                                <td>{{ $barangMasuk->nomer_container }}</td>
                                                <td>{{ $barangMasuk->fifo_in }}</td>
                                                <td>{{ $barangMasuk->fifo_out }}</td>
                                                <td>{{ $barangMasuk->fifo_sisa }}</td>
                                                <td>
                                                    <a href="{{ route('data-gudang.barang-masuk.edit', $barangMasuk->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('data-gudang.barang-masuk.destroy', $barangMasuk->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
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
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
    
    <!-- Page-specific script -->
    <script>
        $(document).ready(function() {
            $('#barangMasukTable').DataTable();
        });
    </script>
</body>
</html>
