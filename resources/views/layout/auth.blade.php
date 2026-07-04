<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBUM</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-sbum.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f5f2ec] text-[#33403b] font-sans antialiased min-h-screen">
    @yield('content')
</body>
</html>
