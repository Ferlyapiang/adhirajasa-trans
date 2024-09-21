<!DOCTYPE html>
<html>
<head>
    <title>User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 140px;
            max-height: 60px;
        }
        .header h1 {
            color: #333;
            font-size: 24px;
            margin: 10px 0;
        }
        .details {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #e9f7f9;
            border-left: 5px solid #3D6FFB;
            border-radius: 4px;
        }
        .details p {
            font-size: 16px;
            line-height: 1.5;
            margin: 5px 0;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .footer p {
            margin: 0;
        }
        .highlight {
            font-weight: bold;
            color: #3D6FFB;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo">
            <h1>User Details</h1>
        </div>
        <div class="details">
            <p><strong class="highlight">Name:</strong> {{ $user->name }}</p>
            <p><strong class="highlight">Email:</strong> {{ $user->email }}</p>
            <p><strong class="highlight">Status:</strong> {{ ucfirst($user->status) }}</p>
            @if(isset($plainPassword))
            <p><strong class="highlight">Plain Password:</strong> {{ $plainPassword }}</p>
            @endif
        </div>
        <p>This document contains the details of the newly created user. Please keep this information secure.</p>
        <p>From: <strong>{{ date('d F Y') }}</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Adhirajasa Trans Sejahtera. All rights reserved.</p>
    </div>
</body>
</html>
