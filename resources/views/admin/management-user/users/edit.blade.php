<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
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
                            <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px;">Management User | User</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Edit User</h1>
            
                <form id="userForm" action="{{ route('management-user.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
            
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
            
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        <span id="emailError" style="color: red; display: none;">Email already exists.</span>
                    </div>
            
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
            
                    <div class="form-group">
                        <label for="group_id">Group</label>
                        <select id="group_id" name="group_id" class="form-control">
                            <option value="">None</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ $group->id == $user->group_id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
            <!-- /.main content -->

        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.footer')
        <!-- /.footer -->

    </div>
    <!-- ./wrapper -->

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    User updated successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#userForm').on('submit', function(event) {
                var email = $('#email').val();
                var currentEmail = "{{ $user->email }}"; // Current email to exclude from check
                
                if (email !== currentEmail) {
                    $.post("{{ route('check-email') }}", { email: email, _token: "{{ csrf_token() }}" }, function(response) {
                        if (response.exists) {
                            $('#emailError').show();
                            event.preventDefault(); // Prevent form submission
                        } else {
                            $('#emailError').hide();
                        }
                    });
                }
            });

            // Check if the session has a success message
            if ("{{ session('success') }}") {
                $('#successModal').modal('show');
            }
        });
    </script>
</body>

</html>
