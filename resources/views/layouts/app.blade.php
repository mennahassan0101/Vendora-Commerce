<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vendora') — Vendora</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fonts

    @stack('styles')
</head>
<body class="bg-white text-ink font-sans antialiased">
    
    @include('partials.header')
     @include('partials.flash')

    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>