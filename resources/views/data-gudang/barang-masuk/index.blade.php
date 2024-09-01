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
            display: block;
            /* Makes the table scrollable */
            overflow-x: auto;
            /* Enables horizontal scrolling */
            white-space: nowrap;
        }

        #barangMasukTable thead {
            background-color: transparent;
            /* Remove background color from header */
        }

        #barangMasukTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
            text-align: center;
            /* Center-align header text */
        }

        #barangMasukTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #barangMasukTable th[colspan="3"] {
            background-color: transparent;
            /* Remove background color from combined header */
        }

        #barangMasukTable th {
            background-color: transparent;
            /* Remove background color from all headers */
        }

        #barangMasukTable td:nth-child(9) {
            background-color: #d1e7dd;
            /* Light green for FIFO IN */
        }

        #barangMasukTable td:nth-child(10) {
            background-color: #f8d7da;
            /* Light red for FIFO OUT */
        }

        #barangMasukTable td:nth-child(11) {
            background-color: #fff3cd;
            /* Light yellow for FIFO Sisa */
        }

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
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Tanggal Masuk</th>
                                                <th rowspan="2">No Ref (JOC)</th>
                                                <th rowspan="2">Nama Barang</th>
                                                <th rowspan="2">Nama Pemilik</th>
                                                <th rowspan="2">Jenis Mobil</th>
                                                <th rowspan="2">Nomer Polisi</th>
                                                <th rowspan="2">Nomer Container</th>
                                                <th colspan="3">FIFO</th>
                                                <th rowspan="2">Detail</th>
                                            </tr>
                                            <tr>
                                                <th>IN</th>
                                                <th>OUT</th>
                                                <th>SISA</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            @foreach ($barangMasuks as $barangMasuk)
                                                @foreach ($barangMasuk->items as $item)
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $barangMasuk->tanggal_masuk }}</td>
                                                    <td>
                                                        <a href="{{ route('data-gudang.barang-masuk.detail', $barangMasuk->id) }}">
                                                            {{ $barangMasuk->joc_number }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->barang->nama_barang }}</td>
                                                    <td>{{ $barangMasuk->customer->name }}</td>
                                                    <td>{{ $barangMasuk->jenis_mobil }}</td>
                                                    <td>{{ $barangMasuk->nomer_polisi }}</td>
                                                    <td>{{ $barangMasuk->nomer_container }}</td>
                                                    <td>{{ $barangMasuk->fifo_in }}</td>
                                                    <td>{{ $barangMasuk->fifo_out }}</td>
                                                    <td style="font-weight: bold">{{ $barangMasuk->fifo_sisa }}</td>
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
