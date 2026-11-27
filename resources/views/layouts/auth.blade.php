<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login Admin — REL | LIV Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-botanical text-inkAdmin font-sans min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <img src="{{ asset('aset/section2_bunga.webp') }}" alt="" class="absolute -top-12 -left-12 w-64 md:w-80 opacity-40 pointer-events-none -rotate-12 select-none">
    <img src="{{ asset('aset/section2_bunga(2).webp') }}" alt="" class="absolute -bottom-12 -right-12 w-64 md:w-80 opacity-40 pointer-events-none rotate-12 select-none">

    @yield('content')
</body>
</html>
