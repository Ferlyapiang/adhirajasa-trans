<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Bank Perusahaan</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

    <!-- xlxs library for exporting Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>

    <style>
        /* Custom styling for the DataTable */
        #bankDataTable {
            border-radius: 10px;
            overflow: hidden;
        }

        #bankDataTable thead {
            background-color: #f8f9fa;
        }

        #bankDataTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #bankDataTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #bankDataTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #bankDataTable tbody tr:last-child td {
            border-bottom: 0;
        }

        .status-active {
            background-color: #d4edda;
            /* Light green */
            color: #155724;
            /* Dark green text */
        }

        .status-inactive {
            background-color: #f8d7da;
            /* Light red */
            color: #721c24;
            /* Dark red text */
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
                        <div class="col-sm-12"
                            style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Master Data |</span>
                                <span>Data Bank Perusahaan</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <a href="{{ route('master-data.bank-data.create') }}" class="btn btn-primary mb-3">Add Bank Data</a>
                <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>

                <div class="table-responsive">
                    <table id="bankDataTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Nama Rekening</th>
                                <th>Nama Gudang</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bankDatas as $index => $bankData)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $bankData->bank_name }}</td>
                                <td>{{ $bankData->account_number }}</td>
                                <td>{{ $bankData->account_name }}</td>
                                <td>{{ $bankData->warehouse->name ?? 'N/A' }}</td>
                                <td
                                    class="{{ $bankData->status == 'active' ? 'status-active' : 'status-inactive' }} text-center">
                                    {{ ucfirst($bankData->status) }}
                                </td>
                                <!-- <td>{{ ucfirst($bankData->status) }}</td> -->
                                <td>
                                    <a href="{{ route('master-data.bank-data.edit', $bankData) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('master-data.bank-data.destroy', $bankData) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

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
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bankDataTable').DataTable();
        });
        document.getElementById('exportButton').addEventListener('click', function() {

        var table = document.getElementById('bankDataTable');

        var clonedTable = table.cloneNode(true);

        var rows = clonedTable.querySelectorAll('tr');
        rows.forEach(function(row) {
            row.removeChild(row.lastElementChild);
        });

        var workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Bank Data" });

        XLSX.writeFile(workbook, 'BankData.xlsx');
    });
    </script>
</body>

</html>