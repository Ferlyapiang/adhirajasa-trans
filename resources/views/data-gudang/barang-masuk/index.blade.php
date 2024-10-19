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

    <!-- xlxs library for exporting Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.header')

        <!-- Main Sidebar Container -->
        <x-sidebar />

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

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="{{ route('data-gudang.barang-masuk.create') }}" class="btn btn-primary float-right">Tambah Barang Masuk</a>
                                    <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="barangMasukTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal Masuk<br><input type="text" id="searchTanggalMasuk" class="form-control form-control-sm" placeholder="Cari Tanggal"></th>
                                                    <th>Job Order</th>
                                                    <th>Nama Barang</th>
                                                    <th>Nama Pemilik<br><input type="text" id="searchNamaPemilik" class="form-control form-control-sm" placeholder="Cari Nama"></th>
                                                    <th>Gudang</th>
                                                    <th>Jenis Mobil</th>
                                                    <th>Nomer Polisi</th>
                                                    <th>Nomer Container</th>
                                                    <th>Notes</th>
                                                    <th>FIFO IN</th>
                                                    <th>FIFO OUT</th>
                                                    <th>SISA</th>
                                                    <th>Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($barangMasuks as $index => $barangMasuk)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $barangMasuk->tanggal_masuk }}</td>
                                                        <td>
                                                            <a href="{{ route('data-gudang.barang-masuk.detail', $barangMasuk->barang_masuk_id) }}">
                                                                {{ $barangMasuk->joc_number }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $barangMasuk->nama_barang }}</td>
                                                        <td>{{ $barangMasuk->nama_customer }}</td>
                                                        <td>{{ $barangMasuk->nama_gudang }}</td>
                                                        <td>{{ $barangMasuk->nama_type_mobil }}</td>
                                                        <td>{{ $barangMasuk->nomer_polisi }}</td>
                                                        <td>{{ $barangMasuk->nomer_container }}</td>
                                                        <td>{{ $barangMasuk->notes }}</td>
                                                        <td>{{ $barangMasuk->fifo_in }}</td>
                                                        <td>{{ $barangMasuk->fifo_out }}</td>
                                                        <td style="font-weight: bold">{{ $barangMasuk->fifo_sisa }}</td>
                                                        <td>
                                                            <a href="{{ route('data-gudang.barang-masuk.edit', $barangMasuk->barang_masuk_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                            <form action="{{ route('data-gudang.barang-masuk.destroy', $barangMasuk->barang_masuk_id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="10" style="text-align: right;">Total:</th>
                                                    <th id="totalFifoIn"></th>
                                                    <th id="totalFifoOut"></th>
                                                    <th id="totalFifoSisa"></th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('admin.footer')
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Page-specific script -->
    <script>
        $(document).ready(function() {
            var table = $('#barangMasukTable').DataTable({
        
                    initComplete: function () {
                        this.api().columns([1, 4]).every(function () {
                            var column = this;
                            $('input', this.header()).on('keyup change clear', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                        });
                    }
                });
            function updateTotals() {
                let totalFifoIn = 0;
                let totalFifoOut = 0;
                let totalFifoSisa = 0;

                table.rows({ filter: 'applied' }).every(function() {
                    var data = this.data();
                    totalFifoIn += parseFloat(data[10]) || 0;
                    totalFifoOut += parseFloat(data[11]) || 0;
                    totalFifoSisa += parseFloat(data[12]) || 0;
                });

                $('#totalFifoIn').text(totalFifoIn);
                $('#totalFifoOut').text(totalFifoOut);
                $('#totalFifoSisa').text(totalFifoSisa);
            }

            updateTotals();
            table.on('search.dt', function() {
                updateTotals();
            });
        });

        document.getElementById('exportButton').addEventListener('click', function() {
            var table = document.getElementById('barangMasukTable');
            var clonedTable = table.cloneNode(true);

            var rows = clonedTable.querySelectorAll('tr');
            rows.forEach(function(row) {
                row.removeChild(row.lastElementChild);
            });

            var workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Data Barang Masuk" });
            XLSX.writeFile(workbook, 'DataBarangMasuk.xlsx');
        });
    </script>
</body>

</html>
