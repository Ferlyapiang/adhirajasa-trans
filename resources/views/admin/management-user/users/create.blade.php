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
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }

        #homeButton {
            font-size: 1.2rem; /* Increase button font size */
            padding: 10px 20px; /* Increase button padding */
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
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Management User |</span>
                                <span>Users</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container-fluid pl-4">
                <h1>Add User</h1>

                <form id="userForm" action="{{ route('management-user.users.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        <span id="emailError" style="color: red; display: none;">Email already exists.</span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <span id="passwordRequirements" style="color: red; display: none;">
                            Password must contain at least one uppercase letter, one number, and one special character.
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        <span id="passwordMismatch" style="color: red; display: none;">Passwords do not match.</span>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="group_id">Group</label>
                        <select id="group_id" name="group_id" class="form-control" required>
                            <option value="" disabled selected hidden>Select Group</option>
                            @foreach(App\Models\Group::all() as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                        <span id="groupError" style="color: red; display: none;">Please select a group.</span>
                    </div>

                    <div class="form-group">
                        <label for="warehouse_id">Warehouse</label>

                        @php
                        $loggedInUser = Auth::user();
                    
                        // Redirect to login if the user is not authenticated
                        if (!$loggedInUser) {
                            return redirect()->route('login')->with('alert', 'You must be logged in to access this page.');
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

                    <button type="submit" id="submitButton" class="btn btn-primary mb-3">Save User</button>
                    <a id="homeButton" href="/management-user/users" class="btn btn-success" style="display: none; margin-top: 10px;">Kembali ke Home</a>
                    <div id="successMessage" class="success-message" style="display: none;">Data Sudah Berhasil disimpan!</div>
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
                    User added successfully!
                    <div class="success-message" style="display: block;">Data Sudah Berhasil disimpan!</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#userForm').on('submit', function(event) {
            var password = $('#password').val();
            var passwordConfirmation = $('#password_confirmation').val();

            if (password !== passwordConfirmation) {
                $('#passwordMismatch').show();
                event.preventDefault();
            } else {
                $('#passwordMismatch').hide();
                // Hide the submit button and show the home button after form submission
                $('#submitButton').hide();
                $('#homeButton').show();
                $('#successMessage').show(); // Show success message
                
                // Make all inputs readonly
                $('#userForm input, #userForm select').prop('readonly', true);
            }
        });

        if ("{{ session('success') }}") {
            $('#successModal').modal('show');
            // Ensure the buttons are correctly set on success
            $('#submitButton').hide();
            $('#homeButton').show();
            $('#successMessage').show(); // Show success message
            
            // Make all inputs readonly
            $('#userForm input, #userForm select').prop('readonly', true);
        }

        $('#email').on('blur', function() {
            var email = $(this).val();

            $.ajax({
                url: "{{ route('check-email') }}",
                method: 'POST',
                data: {
                    email: email,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.exists) {
                        $('#emailError').show();
                    } else {
                        $('#emailError').hide();
                    }
                }
            });
        });

        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const passwordRequirements = document.getElementById('passwordRequirements');
            const passwordRegex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/;

            if (!passwordRegex.test(password)) {
                passwordRequirements.style.display = 'block';
            } else {
                passwordRequirements.style.display = 'none';
            }
        });

    </script>
</body>

</html>
