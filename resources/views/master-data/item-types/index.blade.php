<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tipe Data Barang - Adhirajasa Trans Sejahtera</title>

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

    <!-- XLSX library for exporting Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>

    <style>
        /* Custom styling for the DataTable */
        #itemTypeTable {
            border-radius: 10px;
            overflow: hidden;
        }

        #itemTypeTable thead {
            background-color: #f8f9fa;
        }

        #itemTypeTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #itemTypeTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #itemTypeTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #itemTypeTable tbody tr:last-child td {
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
                                <span style="font-weight: 370;">Management Master Data |</span>
                                <span>Tipe Data Barang</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Tipe Data Barang</h1>
                <a href="{{ route('master-data.item-types.create') }}" class="btn btn-primary mb-3">Add Tipe Barang</a>

                <button id="downloadXlsx" class="btn btn-success mb-3">Download to XLSX</button>

                <div class="table-responsive">
                    <table id="itemTypeTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itemTypes as $index => $itemType)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $itemType->name }}</td>
                                    <td>{{ ucfirst($itemType->status) }}</td>
                                    <td>
                                        <a href="{{ route('master-data.item-types.edit', $itemType) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('master-data.item-types.destroy', $itemType) }}" method="POST" style="display:inline;">
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
            $('#itemTypeTable').DataTable();

            // Export to XLSX
            $('#downloadXlsx').on('click', function() {
                var wb = XLSX.utils.book_new(); // Create a new workbook
                var ws_data = [];

                // Get the table header (excluding the "Actions" column)
                var headers = [];
                $('#itemTypeTable thead th').each(function(index) {
                    if (index !== 3) { // Exclude the "Actions" column
                        headers.push($(this).text());
                    }
                });
                ws_data.push(headers);

                // Get the table body (excluding the "Actions" column)
                $('#itemTypeTable tbody tr').each(function() {
                    var row = [];
                    $(this).find('td').each(function(index) {
                        if (index !== 3) { // Exclude the "Actions" column
                            row.push($(this).text().trim());
                        }
                    });
                    ws_data.push(row);
                });

                // Create a new worksheet and append the data
                var ws = XLSX.utils.aoa_to_sheet(ws_data);
                XLSX.utils.book_append_sheet(wb, ws, 'ItemTypes');

                // Export the file as XLSX
                XLSX.writeFile(wb, 'item_types_data.xlsx');
            });
        });
    </script>
</body>

</html>
