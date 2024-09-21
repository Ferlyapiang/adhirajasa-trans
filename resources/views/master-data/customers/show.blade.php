<!-- resources/views/customers/show.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Details</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        .confirmation-dialog {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .confirmation-dialog button {
            margin: 0 5px;
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
                            <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px;">Customer Details</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">    
                <h1>Detail Customer</h1>
                <div class="card">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Name:</dt>
                            <dd class="col-sm-9">{{ $customer->name }}</dd>

                            <dt class="col-sm-3">No NPWP/KTP:</dt>
                            <dd class="col-sm-9">{{ $customer->no_npwp_ktp }}</dd>

                            <dt class="col-sm-3">No HP:</dt>
                            <dd class="col-sm-9">{{ $customer->no_hp }}</dd>

                            <dt class="col-sm-3">Email:</dt>
                            <dd class="col-sm-9">{{ $customer->email }}</dd>

                            <dt class="col-sm-3">Address:</dt>
                            <dd class="col-sm-9">{{ $customer->address }}</dd>

                            <dt class="col-sm-3">Status:</dt>
                            <dd class="col-sm-9">{{ $customer->status }}</dd>

                            <dt class="col-sm-3">Created At:</dt>
                            <dd class="col-sm-9">{{ $customer->created_at ? $customer->created_at->format('d-m-Y H:i:s') : '-' }}</dd>

                            <dt class="col-sm-3">Updated At:</dt>
                            <dd class="col-sm-9">{{ $customer->updated_at ? $customer->updated_at->format('d-m-Y H:i:s') : '-' }}</dd>
                        </dl>
                        <a href="{{ route('master-data.customers.edit', $customer) }}" class="btn btn-warning">Edit</a>
                        <button type="button" class="btn btn-danger" id="delete-button">Delete</button>
                        <a href="{{ route('master-data.customers.index') }}" class="btn btn-secondary">Back to List</a>
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

    <div id="confirmation-dialog" class="confirmation-dialog">
        <p>Are you sure you want to delete this customer?</p>
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" onclick="closeDialog()">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>

    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
    <script>
        document.getElementById('delete-button').addEventListener('click', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('master-data.customers.destroy', $customer) }}";

            const csrfField = document.createElement('input');
            csrfField.type = 'hidden';
            csrfField.name = '_token';
            csrfField.value = '{{ csrf_token() }}';
            form.appendChild(csrfField);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);
            document.getElementById('confirmation-dialog').style.display = 'block';

            document.querySelector('#confirmation-dialog button[type="submit"]').onclick = function() {
                form.submit();
            };
        });

        function closeDialog() {
            document.getElementById('confirmation-dialog').style.display = 'none';
        }
    </script>
</body>
</html>
