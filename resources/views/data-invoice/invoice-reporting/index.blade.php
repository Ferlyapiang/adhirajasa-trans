<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Reporting Invoice</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
</head>

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
                            <h1 class="m-0">Data Reporting Invoice</h1>
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
                                    <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="ownerNameFilter">Nama Pemilik:</label>
                                        <select id="ownerNameFilter" class="form-control">
                                            <option value="">Semua</option>
                                            @foreach ($owners as $owner)
                                                <option value="{{ $owner }}">{{ $owner }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="tanggalTagihanFilter">Tanggal Tagihan:</label>
                                        <select id="tanggalTagihanFilter" class="form-control">
                                            <option value="">Semua</option>
                                            @foreach ($tanggalTagihans as $tanggalTagihan)
                                                <option value="{{ $tanggalTagihan }}">{{ $tanggalTagihan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table id="barangMasukTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomer Invoice</th>
                                                <th>Tanggal Penagihan</th>
                                                <th>Nomer Referensi</th>
                                                <th>Nama Pemilik</th>
                                                <th>Gudang</th>
                                                <th>Tanggal Masuk Penimbunan</th>
                                                <th>Tanggal Keluar Penimbunan</th>                                                
                                                <th>Total QTY Sisa</th>
                                                <th>Lemburan</th>
                                                <th>Harga Kirim Barang</th>
                                                <th>Total Harga Simpan</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoiceMaster as $index => $item)
                                                <tr>
                                                    <!-- Individual checkbox -->
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <form action="{{ route('invoices-report.show') }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="nomer_invoice"
                                                                value="{{ $item->nomer_invoice }}">
                                                            <button type="submit"
                                                                style="background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;">
                                                                {{ $item->nomer_invoice ?? 'X' }}
                                                            </button>
                                                        </form>
                                                    </td>

                                                    <td>
                                                        {{ $item->tanggal_tagihan ?? ($item->tanggal_tagihan ?? 'X') }}
                                                    </td>
                                                    <td>{{ $item->joc_number ?? ($item->nomer_surat_jalan ?? 'X') }}</td>
                                                    <td>{{ $item->customer_name ?? 'X' }}
                                                    <td>
                                                        {{ $item->warehouse_name ?? 'X' }}
                                                    </td>
                                                    <td>{{ $item->tanggal_masuk_penimbunan ?? 'X' }}</td>
                                                    <td>{{ $item->tanggal_keluar_penimbunan ?? 'X' }}</td>
                                                    <td>{{ $item->total_sisa ?? 'X' }}</td>
                                                    <td>{{ number_format($item->harga_lembur) ?? 'X' }}</td>
                                                    <td>{{ number_format($item->harga_kirim_barang) ?? 'X' }}</td>
                                                    <td>
                                                        {{ number_format($item->harga_simpan_barang) ?? 'X' }}
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('data-invoice.invoice-reporting.delete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="nomer_invoice" value="{{ $item->nomer_invoice }}">
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
    
    <!-- xlxs library for exporting Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Page-specific script -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#barangMasukTable').DataTable();

            $('#ownerNameFilter').select2({
                placeholder: "Pilih Pemilik", // Optional placeholder
                allowClear: true // Optional to allow clearing the selection
            }).on('change', function() {
                var filterValue = $(this).val();
                table.column(4).search(filterValue).draw();
                calculateTotals(); // Hitung ulang total setelah filter diterapkan
                updateSelectAllCheckboxState(); // Periksa ulang state checkbox
            });

            $('#tanggalTagihanFilter').select2({
                placeholder: "Pilih Tanggal Tagihan", // Optional placeholder
                allowClear: true // Optional to allow clearing the selection
            }).on('change', function() {
                var filterValue = $(this).val();
                table.column(2).search(filterValue).draw();
                updateSelectAllCheckboxState(); // Periksa ulang state checkbox
            });

        });

        document.getElementById('exportButton').addEventListener('click', function() {
            var table = document.getElementById('barangMasukTable');
            var clonedTable = table.cloneNode(true);

            // Convert the cloned table to a workbook and save it as an Excel file
            var workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Data Barang Masuk" });
            XLSX.writeFile(workbook, 'DataReportingInvoice.xlsx');
        });

    </script>


</body>

</html>
