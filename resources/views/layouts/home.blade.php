<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>@yield('title', 'Nupilca C.A')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="webp" href="{{ asset('img/logo.jpg') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stack('styles')
</head>
<body>
    @include('partials.h-header')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.h-footer')
    
    <script src="{{ asset('js/home.js') }}"></script>
    @stack('scripts')
</body>
</html>