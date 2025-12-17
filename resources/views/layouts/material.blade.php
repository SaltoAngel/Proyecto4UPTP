<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

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
        /* DataTables: arreglar paginación con texto (Primero/Anterior/Siguiente/Último) */
        .dataTables_wrapper .dataTables_paginate .pagination .page-link {
            width: auto !important;
            height: auto !important;
            min-width: 2.25rem;
            padding: .375rem .75rem;
            border-radius: .375rem !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1.2;
        }
        .dataTables_wrapper .dataTables_paginate .pagination .page-item { margin: 0 .125rem; }
        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link {
            background: #1a73e8;
            border-color: #1a73e8;
            color: #fff;
        }
        /* Compatibilidad de inputs en modales con Material CSS */
        .modal .modal-content { background-color: #fff; }
        .modal .form-label, .modal .form-check-label { color: #344767; }
        .modal .form-control, .modal .form-select, .modal textarea {
            background-color: #fff !important;
            color: #344767 !important;
            border: 1px solid #d2d6da !important;
        }
        .modal .form-control:disabled, .modal .form-select:disabled, .modal textarea:disabled {
            background-color: #e9ecef !important;
            opacity: 1;
        }
        .modal .form-control::placeholder, .modal textarea::placeholder { color: #9ca3af !important; opacity: 1; }
        .modal .input-group-text { background-color: #f8f9fa; color: #344767; border: 1px solid #d2d6da; }
        /* Canvas de mapa: ocupar ancho completo */
        .geo-canvas { width: 100% !important; display: block; }
        /* Overrides globales para formularios (fuera de modales) */
        .card .form-label, .form-label { color: #344767; }
        .card .form-control, .card .form-select, .card textarea,
        .form-control, .form-select, textarea {
            background-color: #fff !important;
            color: #344767 !important;
            border: 1px solid #d2d6da !important;
        }
        .form-control:disabled, .form-select:disabled, textarea:disabled {
            background-color: #e9ecef !important;
            opacity: 1;
        }
        .form-control::placeholder, textarea::placeholder { color: #9ca3af !important; opacity: 1; }
        .input-group-text { background-color: #f8f9fa; color: #344767; border: 1px solid #d2d6da; }
    </style>
    @stack('styles')
</head>
<body class="g-sidenav-show  bg-gray-200">
    @include('partials.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        @include('partials.header')

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
    <!-- Geo charts dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/topojson-client@3.1.0/dist/topojson-client.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-geo@4.3.0/build/index.umd.min.js"></script>
    <script src="{{ asset('material/js/material-dashboard.min.js') }}"></script>
    @stack('scripts')
</body>
</html>
