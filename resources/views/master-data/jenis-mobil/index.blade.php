<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jenis Mobil - Adhirajasa Trans Sejahtera</title>

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
        #jenisMobilTable {
            border-radius: 10px;
            overflow: hidden;
        }

        #jenisMobilTable thead {
            background-color: #f8f9fa;
        }

        #jenisMobilTable thead th {
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid #dee2e6;
        }

        #jenisMobilTable tbody tr {
            border-bottom: 1px solid #dee2e6;
        }

        #jenisMobilTable tbody td {
            border-left: 1px solid #dee2e6;
        }

        #jenisMobilTable tbody tr:last-child td {
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
                                <span>Jenis Mobil</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Jenis Mobil</h1>
                <a href="{{ route('master-data.jenis-mobil.create') }}" class="btn btn-primary mb-3">Add Jenis Mobil</a>

                <div class="table-responsive">
                <table id="jenisMobilTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Mobil</th>
                            <th>Harga Sewa</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jenisMobil as $index => $mobil)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $mobil->type }}</td>
                                <td>{{ number_format($mobil->rental_price, 2) }}</td>
                                <td>{{ ucfirst($mobil->status) }}</td>
                                <td>
                                    <a href="{{ route('master-data.jenis-mobil.edit', $mobil) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('master-data.jenis-mobil.destroy', $mobil) }}" method="POST" style="display:inline;">
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
            $('#jenisMobilTable').DataTable();
        });
    </script>
</body>

</html>
