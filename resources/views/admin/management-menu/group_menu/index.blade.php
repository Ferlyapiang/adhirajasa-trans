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

    <style>
        /* Custom styling for the DataTable */
        #menuTable {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        #menuTable thead {
            background-color: #f8f9fa;
        }

        #menuTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #menuTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #menuTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #menuTable tbody tr:last-child td {
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
        <div class="content-wrapper p-4">

            <!-- Content Header (Page header) -->
            <div class="container">
                <h1>Group Menus</h1>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <a href="{{ route('management-menu.group_menu.create') }}" class="btn btn-primary mb-3">Add menus</a>
                <table id="menuTable" class="table mt-4">
                    <thead>
                        <tr>
                            <th>Group</th>
                            <th>Menu</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupMenus as $groupMenu)
                            <tr>
                                <td>{{ $groupMenu->group->name }}</td>
                                <td>{{ $groupMenu->menu->name }}</td>
                                <td>
                                    <form action="{{ route('management-menu.group_menu.destroy', $groupMenu->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
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

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#menuTable').DataTable({
                "paging": true,
                "lengthChange": true, // Allow changing the number of entries per page
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100], // Options for entries per page
                "pageLength": 10 // Default number of entries to show
            });
        });
    </script>
</body>

</html>
