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
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Management Menu |</span> 
                                <span>Data Menu</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4 table-responsive">
                 
                <h1>Menu Table</h1>
                <a href="{{ route('management-menu.menus.create') }}" class="btn btn-primary mb-3">Add menus</a>

                <!-- Log Data Table -->
                <table id="menuTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>URL</th>
                            <th>Router</th>
                            <th>Icon</th>
                            <th>Active</th>
                            <th>Priority</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->url }}</td>
                            <td>{{ $menu->router }}</td>
                            <td><i class="{{ $menu->icon }}"></i></td>
                            <td>{{ $menu->is_active ? 'Yes' : 'No' }}</td>
                            <td>{{$menu->priority}}</td>
                            <td>
                                <a href="{{ route('management-menu.menus.edit', $menu->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('management-menu.menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this menu?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @if($menu->children)
                        @foreach($menu->children as $submenu)
                        <tr>
                            <td>-- {{ $submenu->name }}</td>
                            <td>{{ $submenu->url }}</td>
                            <td>{{ $submenu->router }}</td>
                            <td><i class="{{ $submenu->icon }}"></i></td>
                            <td>{{ $submenu->is_active ? 'Yes' : 'No' }}</td>
                            <td>{{$menu->priority}}</td>
                            <td>
                                <a href="{{ route('management-menu.menus.edit', $submenu->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('management-menu.menus.destroy', $submenu->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this menu?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @endif
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
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });
    </script>
</body>

</html>
