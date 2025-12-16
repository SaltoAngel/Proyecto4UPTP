<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Material Dashboard')</title>

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="{{ asset('material/css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('material/css/nucleo-svg.css') }}" rel="stylesheet">
    <link href="{{ asset('material/css/material-dashboard.min.css') }}" rel="stylesheet">
    <!-- Font Awesome (compatibilidad con vistas existentes) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/J6MdGPrv3qNQGvZ1bGxG2Q5H6QZ1B6LrZ8Nw3GvG0wP6Vb8rGQ6vG8YJZQ3uFJvGxYf5zC9Yg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Roboto', sans-serif; color: #344767; }
        .sidenav { background: #fff !important; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .sidenav .nav-link { color: #344767; }
        .sidenav .nav-link .material-icons, .sidenav .nav-link i { color: #344767; }
        .sidenav .nav-link.active { background: rgba(52, 71, 103, 0.08); border-radius: .5rem; color: #344767; }
        .navbar.navbar-main { background: #fff; }
        .navbar .navbar-nav .nav-link, .navbar .navbar-nav .nav-link i, .navbar .navbar-nav .nav-link span { color: #344767; }
        .card, .card-header, .card-body { color: #344767; }
    </style>
    @stack('styles')
</head>
<body class="g-sidenav-show  bg-gray-200">
    @include('partials.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <h6 class="font-weight-bolder mb-0">@yield('title', 'Dashboard')</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <ul class="navbar-nav ms-auto justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <span class="d-sm-inline d-none text-muted"><i class="material-icons me-1">person</i>{{ auth()->user()->name ?? 'Usuario' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            @yield('content')
            @include('partials.footer')
        </div>
    </main>

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="{{ asset('material/js/material-dashboard.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
