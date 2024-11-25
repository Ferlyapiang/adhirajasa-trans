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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <style>
        /* Custom styling for the DataTable */
        #customerTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6; /* Optional: border for better visibility */
        }

        #customerTable thead {
            background-color: #f8f9fa;
        }

        #customerTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #customerTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #customerTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #customerTable tbody tr:last-child td {
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

                /* Enhanced status styling with centered text, padding, and border-radius */
        .status-active, .status-inactive {
            display: inline-block;
            padding: 5px 10px; /* Add padding */
            border-radius: 50px; /* Rounded corners */
            font-weight: bold;
            text-transform: uppercase;
            text-align: center; /* Center text */
            margin: 0 auto; /* Center the status element itself if possible */
        }

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
                                <span>Customer</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <a href="{{ route('master-data.customers.create') }}" class="btn btn-primary mb-3">Add Customer</a>
                <a id="downloadXlsx" class="btn btn-success mb-3">Download XLSX</a>
                <div class="table-responsive">
                    <table id="customerTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Name PT</th>
                                <th>No NPWP</th>
                                <th>No KTP</th>
                                <th>No HP</th>
                                <th>Email</th>
                                <th>Tipe Pembayaran</th>
                                <th>Warehouse</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->name_pt }}</td>
                                    <td>{{ $customer->no_npwp }}</td>
                                    <td>{{ $customer->no_ktp }}</td>
                                    <td>{{ $customer->no_hp }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->type_payment_customer }}</td>
                                    <td>{{ $customer->warehouse ? $customer->warehouse->name : 'No Warehouse Found' }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td class="{{ $customer->status == 'active' ? 'status-active' : 'status-inactive' }} text-center">
                                        {{ ucfirst($customer->status) }}
                                    </td>
                                    <td>{{ $customer->created_at ? $customer->created_at->format('d-m-Y H:i:s') : '-' }}</td>
                                    <td>{{ $customer->updated_at ? $customer->updated_at->format('d-m-Y H:i:s') : '-' }}</td>

                                    <td>
                                        <a href="{{ route('master-data.customers.edit', $customer) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('master-data.customers.destroy', $customer) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                        <a href="{{ route('master-data.customers.show', $customer) }}" class="btn btn-info btn-sm">Show</a>
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

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="customerName"></span>? This action cannot be undone.
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
        var table = $('#customerTable').DataTable();

        $('#downloadXlsx').on('click', function() {
    var wb = XLSX.utils.book_new(); // Create a new workbook
    var ws_data = [];

    // Get the table header (excluding the last "Actions" column)
    var headers = [];
    $('#customerTable thead th').each(function(index) {
        if (index !== 12) { // Exclude the "Actions" column
            headers.push($(this).text());
        }
    });
    ws_data.push(headers);

    $('#customerTable tbody tr').each(function() {
        var row = [];
        $(this).find('td').each(function(index) {
            if (index !== 12) { 
                if (index === 9) {
                    row.push($(this).text().trim());
                } else {
                    row.push($(this).text());
                }
            }
        });
        ws_data.push(row);
    });

    // Create a new worksheet and append the data
    var ws = XLSX.utils.aoa_to_sheet(ws_data);
    XLSX.utils.book_append_sheet(wb, ws, 'Customers');

    // Export the file as XLSX
    XLSX.writeFile(wb, 'customers_data.xlsx');
});


        $('#searchInput').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#customerTable').on('click', '.btn-danger', function(event) {
            event.preventDefault();
            var form = $(this).closest('form');
            var customerName = $(this).closest('tr').find('td').first().text();
            var actionUrl = form.attr('action');

            $('#customerName').text(customerName);
            $('#deleteForm').attr('action', actionUrl);

            var modalElement = document.getElementById('confirmDeleteModal');
            var modal = new bootstrap.Modal(modalElement); // Initialize the modal
            modal.show(); // Show the modal
        });

        $('#cancelButton').on('click', function() {
            location.reload(); // Reload the page
        });
    });
    </script>
</body>
</html>
