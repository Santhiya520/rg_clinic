<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $type === 'password_reset' ? 'Password Reset OTP' : 'Email Verification OTP' }} - RG Maruthuvamaiyam
    </title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #1e55a7;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .content {
            padding: 40px 30px;
        }

        .otp-code {
            display: inline-block;
            background: #f0f8ff;
            border: 2px dashed #1e55a7;
            padding: 20px 40px;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #1e55a7;
            border-radius: 8px;
            margin: 20px 0;
        }

        .note {
            background: #fff3cd;
            border: 1px solid #ffecb5;
            padding: 12px;
            border-radius: 6px;
            margin: 20px 0;
            color: #856404;
            font-size: 14px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
<div class="email-container">

    <!-- Header -->
    <div class="header">
        <h1><img src="{{ asset('images/logo.png') }}" style="width:250px"></h1>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Hello,</h2>

        @if($type === 'password_reset')
            <p>
                We received a request to reset your password.
                Please use the One-Time Password (OTP) below to continue.
            </p>
        @else
            <p>
                Thank you for registering with RG Maruthuvamaiyam.
                Please verify your email using the OTP below.
            </p>
        @endif

        <div style="text-align:center;">
            <div class="otp-code">{{ $otp }}</div>
            <p><small>This OTP is valid for 15 minutes</small></p>
        </div>

        <div class="note">
            <strong>⚠️ Important:</strong>
            Do not share this OTP with anyone. Our team will never ask for your OTP.
        </div>

        @if($type === 'password_reset')
            <p>
                If you did not request a password reset, please ignore this email or contact support immediately.
            </p>
        @else
            <p>
                If you did not create an account, please ignore this email.
            </p>
        @endif

        <p>
            Best regards,<br>
            <strong>RG Maruthuvamaiyam Team</strong>
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} RG Maruthuvamaiyam. All rights reserved.</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>

</div>
</body>
</html>
