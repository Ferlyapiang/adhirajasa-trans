<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Bank Data</title>

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
                                <span>Create Bank Data</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1 class="mb-4">Add Bank Data</h1>

                <form action="{{ route('master-data.bank-data.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="bank_name">Nama Bank</label>
        <input type="text" id="bank_name" name="bank_name" class="form-control" required>
    </div>
    <div class="form-group mt-2">
        <label for="account_number">Nomor Rekening</label>
        <input type="text" id="account_number" name="account_number" class="form-control" required>
    </div>
    <div class="form-group mt-2">
        <label for="account_name">Nama Rekening</label>
        <input type="text" id="account_name" name="account_name" class="form-control" required>
    </div>
    <div class="form-group mt-2">
        <label for="warehouse_name">Nama Gudang</label>
        
        <input type="hidden" name="warehouse_id" value="{{ $user->warehouse_id }}">
    
        <select id="warehouse_name" name="warehouse_id" class="form-control" required 
            {{ $user->warehouse_id ? 'disabled' : '' }}>
            
            @if (!$user->warehouse_id)
                <option value="" disabled selected>Pilih Gudang Penyimpanan</option>
            @endif
    
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" 
                    {{ old('warehouse_id') == $warehouse->id || ($user->warehouse_id == $warehouse->id) ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group mt-2">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save</button>
</form>


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
