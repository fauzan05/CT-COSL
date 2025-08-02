<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - {{ config('app.name') }}</title>
    <style>
        /* Style tetap sama seperti template sebelumnya */
        body {
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #374151;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .email-wrapper {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo img {
            max-height: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1f2937;
            font-size: 24px;
            margin: 0;
            margin-bottom: 10px;
        }

        .content {
            margin-bottom: 30px;
        }

        .credentials {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
        }

        .credentials p {
            margin: 8px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563EB;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .support {
            background-color: #fff8f1;
            border-left: 4px solid #2563EB;
            padding: 15px;
            margin: 20px 0;
        }

        .alert {
            background-color: #fee2e2;
            border-left: 4px solid #2563EB;
            padding: 15px;
            margin: 20px 0;
        }

        .password-reset-info {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }

        .password-reset-info p {
            margin: 8px 0;
        }

        .expiry-notice {
            font-size: 14px;
            color: #6b7280;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="logo">
                <img src="{{ $logoBase64 }}" alt="{{ config('app.name') }} Logo">
            </div>

            <div class="header">
                <h1>Reset Your Password</h1>
                <p>We received a request to reset your password</p>
            </div>

            <div class="content">
                <p>Hello {{ $fullname }},</p>

                <p>We received a request to reset the password for your account. If you requested this password reset,
                    please click the button below to create a new password:</p>

                <div class="password-reset-info">
                    <p>Your username: <strong>{{ $username }}</strong></p>
                </div>

                <center>
                    <a href="{{ $reset_link }}" class="button">Reset Password</a>
                    <p class="expiry-notice">This password reset link will expire in 60 minutes.</p>
                </center>

                <div class="alert">
                    <p style="margin: 0;"><strong>Important Security Notice</strong></p>
                    <p style="margin: 8px 0 0;">If you didn't request a password reset, please ignore this email or
                        contact our IT Support team immediately if you believe your account may be compromised.</p>
                </div>

                <div class="support">
                    <p style="margin: 0;"><strong>Need Help?</strong></p>
                    <p style="margin: 8px 0 0;">If you're having trouble with the password reset process or need
                        assistance, please contact our IT Support team:</p>
                    <ul style="margin: 8px 0 0; padding-left: 20px;">
                        <li>Email: it.support@company.com</li>
                        <li>Phone: (021) 555-0123</li>
                        <li>Hours: Monday - Friday, 08:00 - 17:00 WIB</li>
                    </ul>
                </div>
            </div>

            <div class="footer">
                <p>This is an automated email. Please do not reply to this message.</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p>
                    <small>
                        This email was sent to {{ $email }}.
                        If you didn't request a password reset, you can safely ignore this email.
                    </small>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
