<!-- resources/views/customers/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Customer</title>

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
                                <span>Customer</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Create Customer</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('master-data.customers.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="name_pt" class="form-label">Name PT:</label>
                                <input type="text" id="name_pt" name="name_pt" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="selection" class="form-label">Choose Identification Type:</label>
                                <select id="id_selection" class="form-select" onchange="toggleFields()" required>
                                    <option value="">-- Select --</option>
                                    <option value="npwp">NPWP</option>
                                    <option value="ktp">KTP</option>
                                </select>
                            </div>

                            <div id="npwp_field" class="mb-3" style="display:none;">
                                <label for="no_npwp" class="form-label">No NPWP:</label>
                                <input type="text" id="no_npwp" name="no_npwp" class="form-control">
                            </div>

                            <div id="ktp_field" class="mb-3" style="display:none;">
                                <label for="no_ktp" class="form-label">No KTP:</label>
                                <input type="text" id="no_ktp" name="no_ktp" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">No HP:</label>
                                <input type="text" id="no_hp" name="no_hp" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address:</label>
                                <textarea id="address" name="address" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="type_payment_customer" class="form-label">Tipe Pembayaran Customer:</label>
                                <select id="type_payment_customer" name="type_payment_customer" class="form-control" required>
                                    <option value="" disabled selected hidden>Pilih Pembayaran</option>
                                    <option value="Akhir Bulan">Akhir Bulan</option>
                                    <option value="Pertanggal Masuk">Pertanggal Masuk</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="warehouse_id">Warehouse</label>

                                @php
                                    $loggedInUser = Auth::user();
                                    if (!$loggedInUser) {
                                        header('Location: ' . route('login'));
                                        exit;
                                    }

                                    $userWarehouseId = $loggedInUser->warehouse_id;
                                @endphp


                                @if ($userWarehouseId)
                                <select id="warehouse_id" name="warehouse_id" class="form-control" readonly>
                                    <option value="{{ $userWarehouseId }}" selected>
                                        {{ App\Models\Warehouse::find($userWarehouseId)->name }}
                                    </option>
                                </select>
                                @else
                                <select id="warehouse_id" name="warehouse_id" class="form-control" required>
                                    <option value="" disabled selected hidden>Select Warehouse</option>
                                    @foreach(App\Models\Warehouse::all() as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('master-data.customers.index') }}" class="btn btn-secondary">Back to List</a>
                            </div>
                        </form>
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
<script>
    function toggleFields() {
        var selection = document.getElementById('id_selection').value;
        
        // Hide both fields initially
        document.getElementById('npwp_field').style.display = 'none';
        document.getElementById('ktp_field').style.display = 'none';
        
        // Show the corresponding field based on the selection
        if (selection === 'npwp') {
            document.getElementById('npwp_field').style.display = 'block';
        } else if (selection === 'ktp') {
            document.getElementById('ktp_field').style.display = 'block';
        }
    }
</script>

