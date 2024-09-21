<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Menu</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- jQuery -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap Bundle (includes Popper) -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h1 class="m-0">Add New Menu</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Menu</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('management-menu.menus.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Menu Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter menu name" required>
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" name="url" id="url" class="form-control" placeholder="http://example.com">
                            </div>
                            <div class="form-group">
                                <label for="router">Router</label>
                                <input type="text" name="router" id="router" class="form-control" placeholder="Enter router path">
                            </div>
                            <div class="form-group">
                                <label for="icon">Icon Class</label>
                                <input type="text" name="icon" id="icon" class="form-control" placeholder="Enter icon class (e.g., fas fa-home)">
                            </div>
                            <div class="form-group">
                                <label for="is_active">Active <span class="text-danger">*</span></label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Parent Menu (Optional)</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">Select Parent Menu</option>
                                    @foreach($parentMenus as $parentMenu)
                                        <option value="{{ $parentMenu->id }}">{{ $parentMenu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Add Menu</button>
                                <a href="{{ route('management-menu.menus.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
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
</body>

</html>
