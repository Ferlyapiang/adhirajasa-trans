<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            margin: 0;
            line-height: 1.6;
            width: 210mm; /* Lebar A4 */
            height: 297mm; /* Tinggi A4 */
        }
        .container {
            width: 100%;
            max-width: 600px; /* Ukuran maksimum kontainer */
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
            margin-bottom: 24px;
        }
        p {
            font-size: 20px;
            margin: 10px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 16px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('ATSLogo.jpg') }}" style="max-width: 140px; max-height: 60px" alt="ATS Logo">
    </div>
    <div class="container">
        <h1>Password Change Confirmation</h1>
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        <p>Your new password is: <strong>{{ $plainPassword }}</strong></p>
        <p>Your password has been successfully changed.</p>
        <p>From: <strong>{{ date('d F Y') }}</strong></p>
        <p>Thank you for your prompt action!</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Adhirajasa Trans Sejahtera. All rights reserved.</p>
    </div>
</body>
</html>
