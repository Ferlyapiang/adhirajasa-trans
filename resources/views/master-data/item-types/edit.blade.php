<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Item Type</title>

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
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Master Data |</span> 
                                <span>Edit Item Type</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">Edit Item Type</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('master-data.item-types.update', $itemType) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $itemType->name }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control" required>
                                            <option value="" disabled>Select Status</option>
                                            <option value="active" {{ $itemType->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $itemType->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="{{ route('master-data.item-types.index') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
</body>
</html>
