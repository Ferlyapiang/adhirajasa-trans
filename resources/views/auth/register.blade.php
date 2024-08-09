<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <title>Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .custom-register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #FFFFFF;
        }

        .custom-form-container {
            max-width: 400px;
            width: 100%;
        }

        .form-group {
            position: relative;
        }

        .form-control {
            padding-left: 30px;
        }

        .form-control-icon {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
        }

        .form-control-eye {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .btn {
            width: 100%;
        }

        .text-danger {
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm d-flex align-items-center justify-content-center" style="background: #9AB76F;">
                <img src="{{ asset('ats/ATSLogo.png') }}" style="height: 90px;" alt="ATS Logo">
            </div>
            <div class="col-sm">
                <div class="custom-register-container">
                    <div class="custom-form-container">
                        <div class="text-center mb-4">
                            <h2>Register for <span style="color:#4169E1;">ATS Digital</span></h2>
                            <p>Please fill in the details below to create an account.</p>
                        </div>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <span class="fas fa-user form-control-icon"></span>
                                <input type="text" class="form-control" name="name" placeholder="Full Name" required>
                            </div>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <span class="fas fa-envelope form-control-icon"></span>
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
                            </div>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <span class="fas fa-lock form-control-icon"></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <span class="fas fa-eye form-control-eye" id="toggle-password"></span>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <span class="fas fa-lock form-control-icon"></span>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Register</button>
                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="btn btn-secondary">Sudah memiliki Akun? Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
