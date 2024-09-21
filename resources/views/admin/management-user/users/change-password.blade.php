<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, content="initial-scale=1">
    <title>Change Password</title>
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
        }
        .alert {
            display: none;
        }
        .success {
            display: none;
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Change Password for {{ $user->name }}</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="passwordForm" action="{{ route('management-user.users.update-password', $user) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="new_password_confirmation">Confirm New Password</label>
                <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
            </div>
            <div class="alert alert-danger" id="passwordError" style="display: none;"></div>
            <div class="success" id="successMessage">Password berhasil diperbarui!</div>
            <button type="submit" class="btn btn-primary">Update Password</button>
            <a id="backButton" href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </form>

        <a id="homeButton" href="/management-user/users" class="btn btn-success" style="display: none; margin-top: 10px;">Kembali ke Home</a>
    </div>

    <script>
        const form = document.getElementById('passwordForm');
        const newPasswordInput = document.getElementById('new_password');
        const newPasswordConfirmationInput = document.getElementById('new_password_confirmation');
        const errorMessageContainer = document.getElementById('passwordError');
        const successMessage = document.getElementById('successMessage');
        const homeButton = document.getElementById('homeButton');
        const backButton = document.getElementById('backButton');

        form.onsubmit = function(event) {
            event.preventDefault(); // Mencegah form dari submit otomatis

            const newPassword = newPasswordInput.value;
            const confirmationPassword = newPasswordConfirmationInput.value;

            const errors = validatePasswords(newPassword, confirmationPassword);

            if (errors.length > 0) {
                errorMessageContainer.innerHTML = '<ul>' + errors.map(err => `<li>${err}</li>`).join('') + '</ul>';
                errorMessageContainer.style.display = 'block';
                successMessage.style.display = 'none'; // Sembunyikan pesan sukses jika ada kesalahan
            } else {
                // Jika semua pengecekan lolos
                successMessage.style.display = 'block';
                errorMessageContainer.style.display = 'none';

                // Sembunyikan tombol submit dan tombol kembali
                form.querySelector('button[type="submit"]').style.display = 'none';
                backButton.style.display = 'none'; // Sembunyikan tombol Back
                homeButton.style.display = 'block'; // Tampilkan tombol Kembali ke Home

                // Buat form baru untuk melakukan submit
                const newForm = document.createElement('form');
                newForm.method = 'POST';
                newForm.action = form.action;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                newForm.appendChild(csrfInput);

                const passwordInput = document.createElement('input');
                passwordInput.type = 'hidden';
                passwordInput.name = 'new_password';
                passwordInput.value = newPassword;
                newForm.appendChild(passwordInput);

                const confirmPasswordInput = document.createElement('input');
                confirmPasswordInput.type = 'hidden';
                confirmPasswordInput.name = 'new_password_confirmation';
                confirmPasswordInput.value = confirmationPassword;
                newForm.appendChild(confirmPasswordInput);

                document.body.appendChild(newForm);
                newForm.submit();
            }
        };

        function validatePasswords(newPassword, confirmationPassword) {
            const errors = [];
            if (!/[A-Z]/.test(newPassword)) {
                errors.push('Password harus mengandung setidaknya satu huruf besar.');
            }
            if (!/[0-9]/.test(newPassword)) {
                errors.push('Password harus mengandung setidaknya satu angka.');
            }
            if (!/[\W_]/.test(newPassword)) {
                errors.push('Password harus mengandung setidaknya satu simbol.');
            }
            if (newPassword !== confirmationPassword) {
                errors.push('Password konfirmasi tidak cocok.');
            }
            return errors;
        }
    </script>
</body>
</html>
