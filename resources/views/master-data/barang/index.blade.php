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
        #barangTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        #barangTable thead {
            background-color: #f8f9fa;
        }

        #barangTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #barangTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #barangTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #barangTable tbody tr:last-child td {
            border-bottom: 0;
        }

        /* Custom styling for the modal */
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
        }

        /* Optional: Custom button styling */
        .btn-primary, .btn-warning, .btn-danger, .btn-info {
            border-radius: 5px;
        }

        /* Enhanced status styling with centered text and padding */
        .status-active {
            background-color: #d4edda; /* Light green */
            color: #155724; /* Dark green text */
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 50px;

        }

        .status-inactive {
            background-color: #f8d7da; /* Light red */
            color: #721c24; /* Dark red text */
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 50px;
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
                                <span style="font-weight: 370;">Master Data |</span> 
                                <span>Barang</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Data Barang</h1>
                <a href="{{ route('master-data.barang.create') }}" class="btn btn-primary mb-3">Tambah Barang</a>
                <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>
                <table id="barangTable" class="table mt-3">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Pemilik</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barangs as $barang)
                        <tr>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>{{ $barang->jenis }}</td>
                            <td>{{ $barang->customer ? $barang->customer->name : 'N/A' }}</td> <!-- Display customer name -->
                            <td class="{{ $barang->status == 'active' ? 'status-active' : 'status-inactive' }} text-center">
                                {{ ucfirst($barang->status) }}
                            </td>
                            <td>
                                <a href="{{ route('master-data.barang.edit', $barang) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('master-data.barang.destroy', $barang) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
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

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="itemName"></span>? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelButton">Cancel</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>            
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#barangTable').DataTable();

        $('#barangTable').on('click', '.btn-danger', function(event) {
            event.preventDefault();
            var form = $(this).closest('form');
            var itemName = $(this).closest('tr').find('td').first().text();
            var actionUrl = form.attr('action');

            $('#itemName').text(itemName);
            $('#deleteForm').attr('action', actionUrl);

            var modalElement = document.getElementById('confirmDeleteModal');
            var modal = new bootstrap.Modal(modalElement); // Initialize the modal
            modal.show(); // Show the modal
        });

        $('#cancelButton').on('click', function() {
            location.reload(); // Reload the page
        });
    });

    document.getElementById('exportButton').addEventListener('click', function() {

    var table = document.getElementById('barangTable');

    var clonedTable = table.cloneNode(true);

    var rows = clonedTable.querySelectorAll('tr');
    rows.forEach(function(row) {
        row.removeChild(row.lastElementChild);
    });

    var workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Data Master Barang" });

    XLSX.writeFile(workbook, 'DataMasterBarang.xlsx');
    });
    </script>
</body>
</html>
