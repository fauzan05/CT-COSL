<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="icon" type="image/png" href="{{ asset('assets/images/company/company-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/auth.js'])
</head>
<body>
    <div id="auth"></div>
</body>
</html>
