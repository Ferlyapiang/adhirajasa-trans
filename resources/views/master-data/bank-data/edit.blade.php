<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Bank Data</title>

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
                                <span>Edit Bank Data</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container">
                <h1 class="mb-4">Edit Bank Data</h1>

                <form action="{{ route('master-data.bank-data.update', $bankData) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="bank_name">Nama Bank</label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ $bankData->bank_name }}" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="account_number">Nomor Rekening</label>
                        <input type="text" id="account_number" name="account_number" class="form-control" value="{{ $bankData->account_number }}" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="account_name">Nama Rekening</label>
                        <input type="text" id="account_name" name="account_name" class="form-control" value="{{ $bankData->account_name }}" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="warehouse_id">Nama Gudang</label>
                        <select id="warehouse_id" name="warehouse_id" class="form-control" @readonly(true) required>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" {{ $bankData->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="status_bank">Status Bank</label>
                        <select id="status_bank" name="status_bank" class="form-control" required>
                            <option value="" disabled selected>Pilih Status Bank</option>
                            <option value="PT" {{ $bankData->status_bank == 'PT' ? 'selected' : '' }}>PT</option>
                            <option value="Pribadi" {{ $bankData->status_bank == 'Pribadi' ? 'selected' : '' }}>Pribadi</option>
                        </select>
                    </div>
                    
                    <div class="form-group mt-2">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" {{ $bankData->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $bankData->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update</button>
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
