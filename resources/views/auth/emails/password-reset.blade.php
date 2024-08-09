<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h1>Password Reset Request</h1>
    <p>We received a request to reset your password. Click the button below to reset it.</p>
    <a href="{{ url('/password/reset/'.$token) }}" style="display: inline-block; padding: 10px 20px; font-size: 16px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">Reset Password</a>
    <p>If you didn't request a password reset, please ignore this email.</p>
</body>
</html>
