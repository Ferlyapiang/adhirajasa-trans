<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Barang Keluar</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

     <!-- xlxs library for exporting Excel -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>

    <!-- Custom CSS -->
    <style>
        /* Custom styling for the DataTable */
        #barangKeluarTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
            display: block;
            /* Makes the table scrollable */
            overflow-x: auto;
            /* Enables horizontal scrolling */
            white-space: nowrap;
        }

        #barangKeluarTable thead {
            background-color: #f8f9fa;
        }

        #barangKeluarTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #barangKeluarTable tbody tr {
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
        <x-sidebar />
        <!-- /.sidebar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Barang Keluar</h1>
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
                                    <a href="{{ route('data-gudang.barang-keluar.create') }}" class="btn btn-primary float-right">Tambah Barang Keluar</a>
                                    <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>
                                </div>
                                <div class="card-body">
                                    <table id="barangKeluarTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Keluar<br>
                                                <input type="text" id="searchTanggalKeluar" class="form-control form-control-sm" placeholder="Cari Tanggal"></th>
                                                <th>Surat Jalan</th>
                                                {{-- <th>Nomer Invoice</th> --}}
                                                <th>No Ref</th>
                                                <th>Nama Barang</th>
                                                <th>Gudang</th>
                                                <th>Pemilik Barang<br>
                                                <input type="text" id="searchPemilikBarang" class="form-control form-control-sm" placeholder="Cari Pemilik"></th>
                                                <th>qty</th>
                                                <th>Tipe Mobil</th>
                                                <th>Nomer Polisi</th>
                                                <th>Nomer Container</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($barangKeluars as $index => $barangKeluar)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $barangKeluar->tanggal_keluar }}</td>
                                                    <td>
                                                        <a href="{{ route('data-gudang.barang-keluar.showSuratJalan', $barangKeluar->barang_keluar_id) }}">
                                                            {{ $barangKeluar->nomer_surat_jalan }}
                                                        </a>
                                                    </td>
                                                    {{-- <td>
                                                        <a href="{{ route('data-gudang.barang-keluar.show', $barangKeluar->barang_keluar_id) }}">
                                                            {{ $barangKeluar->nomer_invoice }}
                                                        </a>
                                                    </td> --}}
                                                    <td>{{ $barangKeluar->no_ref }}</td>
                                                    <td>{{ $barangKeluar->nama_barang }}</td>
                                                    <td>{{ $barangKeluar->nama_gudang }}</td>
                                                    <td>{{ $barangKeluar->nama_customer }}</td>
                                                    <td>{{ $barangKeluar->qty }}</td>
                                                    <td>{{ $barangKeluar->jenis_mobil_type ?? 'N/A' }}</td>
                                                    <td>{{ $barangKeluar->nomer_polisi }}</td>
                                                    <td>{{ $barangKeluar->nomer_container }}</td>
                                                    <td>
                                                        <a href="{{ route('data-gudang.barang-keluar.edit', $barangKeluar->barang_keluar_id) }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('data-gudang.barang-keluar.destroy', $barangKeluar->barang_keluar_id) }}" method="POST" style="display:inline;">
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
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Page-specific script -->
    <script>
        var table = $('#barangKeluarTable').DataTable({
        initComplete: function () {
            // Pencarian untuk kolom Tanggal Keluar
            this.api().columns([1]).every(function () {
                var column = this;
                $('#searchTanggalKeluar').on('keyup change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });

            // Pencarian untuk kolom Pemilik Barang
            this.api().columns([6]).every(function () {
                var column = this;
                $('#searchPemilikBarang').on('keyup change clear', function () {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
        }
    });
        document.getElementById('exportButton').addEventListener('click', function() {
            var table = document.getElementById('barangKeluarTable');
            var clonedTable = table.cloneNode(true);

            var rows = clonedTable.querySelectorAll('tr');
            rows.forEach(function(row) {
                row.removeChild(row.lastElementChild);
            });

            var workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Data Barang Keluar" });
            XLSX.writeFile(workbook, 'DataBarangKeluar.xlsx');
        });
    </script>
</body>

</html>
