<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- xlxs library for exporting Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>

    <style>
        /* Custom styling for the DataTable */
        #logTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        #logTable thead {
            background-color: #f8f9fa;
        }

        #logTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #logTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #logTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #logTable tbody tr:last-child td {
            border-bottom: 0;
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
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Data Bongkar Muat |</span> 
                                <span>Reporting Bongkar Muat</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4 table-responsive">
                <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>

                <!-- Log Data Table -->
                <table id="logTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Type</th>
                            <th>Tanggal</th>
                            <th>Type Mobil</th>
                            <th>Harga Bongkar/Muat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bongkarMuats as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data->record_type }}</td>
                                <td>{{ $data->tanggal }}</td>
                                <td>{{ $data->type }}</td>
                                <td>Rp {{ number_format($data->rental_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.main content -->

        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.footer')
        <!-- /.footer -->

    </div>
    <!-- ./wrapper -->

    <script>
        $(document).ready(function() {
            // Initialize DataTable with custom settings
            $('#logTable').DataTable({
                paging: true,          // Enable pagination
                searching: true,       // Enable search functionality
                ordering: true,        // Enable column sorting
                responsive: true       // Make the table responsive
            });
            document.getElementById('exportButton').addEventListener('click', function() {

            var table = document.getElementById('logTable');

            var clonedTable = table.cloneNode(true);

            var workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Data Bongkar Muat" });

            XLSX.writeFile(workbook, 'DataBongkarMuat.xlsx');
            });
        });
    </script>
</body>

</html>
