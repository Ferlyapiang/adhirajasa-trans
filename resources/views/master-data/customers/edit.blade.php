<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Customer</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('admin.header')
        <x-sidebar />

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px;">Edit Customer</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid pl-4">
                <h1>Edit Customer</h1>
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('master-data.customers.update', $customer) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ $customer->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="name_pt" class="form-label">Name PT:</label>
                                <input type="text" id="name_pt" name="name_pt" class="form-control" value="{{ $customer->name_pt }}" required>
                            </div>

                            @if(!empty($customer->no_ktp))
                                <div class="mb-3">
                                    <label for="no_ktp" class="form-label">No KTP:</label>
                                    <input type="text" id="no_ktp" name="no_ktp" class="form-control" value="{{ $customer->no_ktp }}">
                                </div>
                            @endif

                            @if(!empty($customer->no_npwp))
                                <div class="mb-3">
                                    <label for="no_npwp" class="form-label">No NPWP:</label>
                                    <input type="text" id="no_npwp" name="no_npwp" class="form-control" value="{{ $customer->no_npwp }}">
                                </div>
                            @endif
                            
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
                                <label for="type_payment_customer" class="form-label">Tipe Pembayaran Customer:</label>
                                <select id="type_payment_customer" name="type_payment_customer" class="form-control" required>
                                    <option value="" disabled selected hidden>Pilih Pembayaran</option>
                                    <option value="Akhir Bulan" {{ $customer->type_payment_customer == 'Akhir Bulan' ? 'selected' : '' }}>Akhir Bulan</option>
                                    <option value="Pertanggal Masuk" {{ $customer->type_payment_customer == 'Pertanggal Masuk' ? 'selected' : '' }}>Pertanggal Masuk</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="warehouse_id">Warehouse</label>
                                @php
                                $loggedInUser = Auth::user();
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
                                    <option value="{{ $warehouse->id }}" {{ $customer->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @endif
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
        </div>

        @include('admin.footer')
    </div>

    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
