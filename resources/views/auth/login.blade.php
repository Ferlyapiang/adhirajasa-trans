<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .custom-login-container {
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

        .form-group {
            position: relative;
        }

        .form-control {
            padding-left: 30px;
        }

        .btn {
            width: 100%;
        }

        .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
        }

        .text-center a {
            display: block;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm" style="background: #9AB76F; display: flex; align-items: center; justify-content: center;">
                <img src="{{ asset('ats/ATSLogo.png') }}" style="height: 90px;" alt="ATS Logo">
            </div>
            <div class="col-sm">
                <div class="custom-login-container">
                    <div class="custom-form-container">
                        <div class="text-center mb-4">
                            <h2>Selamat Datang di <span style="color:#4169E1;">ATS Digital</span></h2>
                            <p>Masukkan Email dan password Adhirajasa Trans Sejahtera Digital untuk masuk.</p>
                        </div>
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <span class="fas fa-envelope form-control-icon"></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email ATS" required>
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <span class="fas fa-lock form-control-icon"></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <span class="fas fa-eye form-control-eye" id="toggle-password"></span>
                            </div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary mt-4">Masuk</button>
                            <div class="text-center mt-3">
                                <a href="{{ route('register') }}" class="btn btn-secondary">Belum punya akun? Daftar</a>
                            </div>
                            <div id="loader" class="text-center mt-3" style="display:none;">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
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
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
