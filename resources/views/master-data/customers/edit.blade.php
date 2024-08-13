<!-- resources/views/customers/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Customer</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
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
                            <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px;">Edit Customer</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Edit Customer</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master-data.customers.update', $customer) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $customer->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_npwp_ktp" class="form-label">No NPWP/KTP:</label>
                                <input type="text" id="no_npwp_ktp" name="no_npwp_ktp" class="form-control" value="{{ $customer->no_npwp_ktp }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP:</label>
                                <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ $customer->no_hp }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ $customer->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address:</label>
                                <textarea id="address" name="address" class="form-control" rows="4" required>{{ $customer->address }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                        <a href="{{ route('master-data.customers.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                    </div>
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

    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>