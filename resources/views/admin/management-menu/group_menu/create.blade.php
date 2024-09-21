<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Group Menu</title>

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
                                <span>Group Menu</span>
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
                                <h4 class="mb-0">Add Group Menu</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('management-menu.group_menu.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="group_id">Group</label>
                                        <select id="group_id" name="group_id" class="form-control" required>
                                            <option value="" disabled selected>Select Group</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('group_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="menu_id">Menu</label>
                                        <select id="menu_id" name="menu_id" class="form-control" required>
                                            <option value="" disabled selected>Select Menu</option>
                                            @foreach ($menus->whereNull('parent_id') as $menu)
                                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('menu_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>



                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                        <a href="{{ route('management-menu.group_menu.index') }}" class="btn btn-secondary">Cancel</a>
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
