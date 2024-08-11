<!-- resources/views/customers/index.blade.php -->
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
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        @include('admin.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.sidebar')
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

                <div class="table-responsive">
                    <table id="customerTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>No NPWP/KTP</th>
                                <th>No HP</th>
                                <th>Email</th>
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
                                    <td>{{ $customer->no_npwp_ktp }}</td>
                                    <td>{{ $customer->no_hp }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td>{{ $customer->status }}</td>
                                    <td>{{ $customer->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $customer->updated_at->format('Y-m-d H:i:s') }}</td>
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
