<!-- resources/views/warehouses/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Warehouse</title>

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
        <x-sidebar />
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px;">Edit Gudang</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container">
                <h1>Edit Gudang</h1>
                <form action="{{ route('master-data.warehouses.update', $warehouse->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Gudang:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $warehouse->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Gudang:</label>
                        <textarea id="address" name="address" class="form-control" rows="4" required>{{ old('address', $warehouse->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Nomer Telpon:</label>
                        <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $warehouse->phone_number) }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="text" id="email" name="email" class="form-control" value="{{ old('email', $warehouse->email) }}">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="active" {{ old('status', $warehouse->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $warehouse->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                <a href="{{ route('master-data.warehouses.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            </div>
            <!-- /.container -->

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
</body>

</html>
