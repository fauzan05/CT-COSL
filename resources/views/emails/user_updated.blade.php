<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information Updated - {{ config('app.name') }}</title>
    <style>
        /* Gunakan style yang sama seperti template sebelumnya */
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

        /* Tambahan style untuk menampilkan perubahan */
        .changes-list {
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }

        .change-item {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .change-item:last-child {
            border-bottom: none;
        }

        .old-value {
            color: #2563EB;
            text-decoration: line-through;
        }

        .new-value {
            color: #22c55e;
            font-weight: 500;
        }

        .alert {
            background-color: #fee2e2;
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
                <h1>Account Information Updated</h1>
                <p>Your account information has been successfully updated.</p>
            </div>

            <div class="content">
                <p>Hello {{ $fullname }},</p>

                <p>This email is to confirm that the following changes have been made to your account:</p>

                <div class="changes-list">
                    @if (isset($changes['fullname']))
                        <div class="change-item">
                            <strong>Full Name:</strong><br>
                            <span class="old-value">{{ $changes['fullname']['old'] }}</span> →
                            <span class="new-value">{{ $changes['fullname']['new'] }}</span>
                        </div>
                    @endif

                    @if (isset($changes['email']))
                        <div class="change-item">
                            <strong>Email Address:</strong><br>
                            <span class="old-value">{{ $changes['email']['old'] }}</span> →
                            <span class="new-value">{{ $changes['email']['new'] }}</span>
                        </div>
                    @endif

                    @if (isset($changes['password']))
                        <div class="change-item">
                            <strong>Password:</strong><br>
                            Your password has been updated
                        </div>
                    @endif
                </div>

                @if (isset($changes['password']))
                    <div class="alert">
                        <p style="margin: 0;"><strong>Important Security Notice</strong></p>
                        <p style="margin: 8px 0 0;">Your password has been changed. If you didn't make this change,
                            please contact our IT Support team immediately.</p>
                    </div>
                @endif

                @if (isset($changes['email']))
                    <div class="alert">
                        <p style="margin: 0;"><strong>Email Change Notice</strong></p>
                        <p style="margin: 8px 0 0;">Your email address has been updated. Future communications will be
                            sent to your new email address.</p>
                    </div>
                @endif

                <center>
                    <a href="{{ route('login') }}" class="button">Login to Your Account</a>
                </center>

                <div class="support">
                    <p style="margin: 0;"><strong>Need Help?</strong></p>
                    <p style="margin: 8px 0 0;">If you didn't authorize these changes or need assistance, please contact
                        our IT Support team:</p>
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
                        This email was sent to {{ isset($changes['email']) ? $changes['email']['new'] : $email }}.
                        If you didn't make these changes, please contact our support team immediately.
                    </small>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
