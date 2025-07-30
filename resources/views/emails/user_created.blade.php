{{-- resources/views/emails/user-created.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        /* Reset CSS */
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
            color: #ffffff !important;
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
    </style>
</head>

<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="logo">
                <img src="{{ $logoBase64 }}" alt="{{ config('app.name') }} Logo">
            </div>

            <div class="header">
                <h1>Welcome to {{ config('app.name') }}!</h1>
                <p>Your account has been successfully created.</p>
            </div>

            <div class="content">
                <p>Hello {{ $fullname }},</p>

                <p>We're excited to have you on board! Your account has been created and is ready to use. Below are your
                    login credentials:</p>

                <div class="credentials">
                    <p><strong>Username:</strong> {{ $username }}</p>
                    <p><strong>Password:</strong> {{ $password }}</p>
                </div>

                <p>For security reasons, we recommend changing your password after your first login.</p>

                <center>
                    <a href="{{ route('login') }}" class="button">Login to Your Account</a>
                </center>

                <div class="support">
                    <p style="margin: 0;"><strong>Need Help?</strong></p>
                    <p style="margin: 8px 0 0;">If you encounter any issues, please don't hesitate to contact our IT
                        Support team:</p>
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
                        This email was sent to {{ $email }}. If you didn't create an account with us,
                        please contact our support team immediately.
                    </small>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
