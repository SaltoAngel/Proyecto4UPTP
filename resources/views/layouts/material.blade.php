{{--
    Layout: Material Dashboard
    Propósito: Estructura base para todas las vistas del panel.
    Incluye:
        - Fuentes, Material CSS, DataTables y SweetAlert.
        - Overrides de estilo (sidebar, navbar, cards, forms, paginación, modales).
        - Scripts: Bootstrap, jQuery, DataTables, Chart.js, Geo (topojson + chartjs-chart-geo), Material Dashboard.
        - Helpers: partials.skeleton (skeletonAttach/skeletonReady) para loading.
        - Partials: sidebar, header, footer.
--}}
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
        /* Forzar que la X de cierre siempre se vea */
        .modal-header .btn-close {
            opacity: 1 !important;
            filter: none !important;
            mix-blend-mode: normal !important;
            background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23ffffff' viewBox='0 0 16 16'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat !important;
            color: #fff !important;
        }
        .modal .form-control:disabled, .modal .form-select:disabled, .modal textarea:disabled {
            background-color: #e9ecef !important;
            opacity: 1;
        }
        .modal .form-control::placeholder, .modal textarea::placeholder { color: #9ca3af !important; opacity: 1; }
        .modal .input-group-text { background-color: #f8f9fa; color: #344767; border: 1px solid #d2d6da; }
        /* Canvas de mapa: ocupar ancho completo */
        .geo-canvas { width: 100% !important; display: block; }
        /* Skeleton loading */
        .skeleton { position: relative; overflow: hidden; background-color: #e9ecef; border-radius: .375rem; }
        .skeleton::after { content: ""; position: absolute; top: 0; left: -150px; height: 100%; width: 150px; background: linear-gradient(90deg, transparent, rgba(255,255,255,.6), transparent); animation: skeleton-shimmer 1.2s ease-in-out infinite; }
        .skeleton-text { height: 12px; margin-bottom: 8px; border-radius: 4px; background-color: #e9ecef; }
        .skeleton-rect { height: 170px; }
        @keyframes skeleton-shimmer { 0% { transform: translateX(0); } 100% { transform: translateX(100%); } }
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
        /* Override paleta primaria a tonos verdes */
        .btn-primary {
            color: #fff !important;
            background-color: #198754 !important;
            border-color: #198754 !important;
            box-shadow: none;
        }
        .btn-primary:hover {
            background-color: #157347 !important;
            border-color: #146c43 !important;
            color: #fff !important;
        }
        .btn-primary:focus, .btn-primary:active, .btn-primary.active, .show > .btn-primary.dropdown-toggle {
            background-color: #146c43 !important;
            border-color: #146c43 !important;
            color: #fff !important;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.3) !important;
        }
        .btn-primary:disabled, .btn-primary.disabled {
            background-color: #b4dfc5 !important;
            border-color: #b4dfc5 !important;
            color: #fff !important;
        }
        .bg-gradient-primary, .bg-primary {
            background: linear-gradient(195deg, #1f9e68 0%, #198754 100%) !important;
        }
        .text-primary { color: #198754 !important; }
        /* Responsivo sidebar + contenido */
        @media (min-width: 992px) {
            #sidenav-main { width: 260px; max-height: 100vh; overflow-y: auto; }
            .main-content { padding-left: 280px; }
        }
        @media (max-width: 991.98px) {
            #sidenav-main { width: 100%; max-width: 320px; max-height: 100vh; overflow-y: auto; }
            .main-content { padding-left: 0; }
            .sidenav .nav-link { padding: 10px 12px; }
        }

        @media (min-width: 1200px) {
        .sidenav.fixed-start + .main-content,
        .g-sidenav-hidden .navbar-vertical:hover.fixed-start + .main-content {
            margin-left: 0 !important;
        }
        .sidenav.fixed-end + .main-content,
        .g-sidenav-hidden .navbar-vertical:hover.fixed-end + .main-content {
            margin-right: 0 !important;
        }
}
    </style>
    @stack('styles')
</head>
<body class="g-sidenav-show  bg-gray-200">
    @include('partials.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Botón hamburguesa visible solo en móviles -->
        <button class="btn btn-link text-secondary d-lg-none position-fixed" id="btn-toggle-sidenav" aria-label="Abrir menú" style="top: 12px; left: 12px; z-index: 1030;">
            <i class="material-icons">menu</i>
        </button>
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
    <!-- Zoom/Pan plugin for Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
    <!-- Geo charts dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/topojson-client@3.1.0/dist/topojson-client.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-geo@4.3.0/build/index.umd.min.js"></script>
    <script src="{{ asset('material/js/material-dashboard.min.js') }}"></script>
    @include('partials.skeleton')
    @stack('scripts')

    <script>
        // Toggle del sidebar en móviles
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('btn-toggle-sidenav');
            const sidenav = document.getElementById('sidenav-main');
            if (!toggleBtn || !sidenav) return;
            toggleBtn.addEventListener('click', () => {
                const isOpen = sidenav.classList.contains('show');
                if (isOpen) {
                    sidenav.classList.remove('show');
                } else {
                    sidenav.classList.add('show');
                }
            });
        });
    </script>

    <!-- Session Alerts Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Entendido'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'Entendido'
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'Entendido'
                });
            @endif
        });
    </script>

    <!-- Debug Panel -->
    <div id="debug-panel" style="position: fixed; bottom: 10px; right: 10px; width: 400px; height: 300px; background: rgba(0,0,0,0.9); color: white; border-radius: 5px; z-index: 9999; display: none; overflow-y: auto; font-size: 12px; padding: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <strong>Debug Panel</strong>
            <button id="debug-toggle" style="background: none; border: none; color: white; cursor: pointer;">×</button>
        </div>
        <div id="debug-logs"></div>
    </div>
    <button id="debug-show" style="position: fixed; bottom: 10px; right: 10px; background: #007bff; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer; z-index: 10000;">🐛</button>

    <script>
        // Debug Panel Script
        const debugPanel = document.getElementById('debug-panel');
        const debugLogs = document.getElementById('debug-logs');
        const debugShow = document.getElementById('debug-show');
        const debugToggle = document.getElementById('debug-toggle');

        let isVisible = false;

        debugShow.addEventListener('click', () => {
            isVisible = !isVisible;
            debugPanel.style.display = isVisible ? 'block' : 'none';
            debugShow.style.display = isVisible ? 'none' : 'block';
        });

        debugToggle.addEventListener('click', () => {
            isVisible = false;
            debugPanel.style.display = 'none';
            debugShow.style.display = 'block';
        });

        // Intercept console.log
        const originalLog = console.log;
        console.log = function(...args) {
            originalLog.apply(console, args);
            const message = args.map(arg => typeof arg === 'object' ? JSON.stringify(arg) : arg).join(' ');
            debugLogs.innerHTML += `<div>[LOG] ${new Date().toLocaleTimeString()}: ${message}</div>`;
            debugLogs.scrollTop = debugLogs.scrollHeight;
        };

        // Intercept fetch
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            debugLogs.innerHTML += `<div>[FETCH] ${new Date().toLocaleTimeString()}: ${args[0]}</div>`;
            debugLogs.scrollTop = debugLogs.scrollHeight;
            return originalFetch.apply(this, args).then(response => {
                debugLogs.innerHTML += `<div>[FETCH RESPONSE] ${new Date().toLocaleTimeString()}: ${response.status} ${response.statusText}</div>`;
                debugLogs.scrollTop = debugLogs.scrollHeight;
                return response;
            }).catch(error => {
                debugLogs.innerHTML += `<div>[FETCH ERROR] ${new Date().toLocaleTimeString()}: ${error.message}</div>`;
                debugLogs.scrollTop = debugLogs.scrollHeight;
                throw error;
            });
        };

        // Intercept XMLHttpRequest
        const originalOpen = XMLHttpRequest.prototype.open;
        XMLHttpRequest.prototype.open = function(method, url, ...args) {
            debugLogs.innerHTML += `<div>[XHR] ${new Date().toLocaleTimeString()}: ${method} ${url}</div>`;
            debugLogs.scrollTop = debugLogs.scrollHeight;
            this.addEventListener('load', () => {
                debugLogs.innerHTML += `<div>[XHR RESPONSE] ${new Date().toLocaleTimeString()}: ${this.status} ${this.statusText}</div>`;
                debugLogs.scrollTop = debugLogs.scrollHeight;
            });
            this.addEventListener('error', () => {
                debugLogs.innerHTML += `<div>[XHR ERROR] ${new Date().toLocaleTimeString()}: Error en solicitud</div>`;
                debugLogs.scrollTop = debugLogs.scrollHeight;
            });
            return originalOpen.apply(this, [method, url, ...args]);
        };

        // Cargar logs de Laravel y status al abrir el panel
        debugShow.addEventListener('click', async () => {
            if (isVisible) {
                try {
                    const response = await fetch('/dashboard/debug-status');
                    const status = await response.json();
                    
                    // Mostrar status de dependencias
                    debugLogs.innerHTML += '<div><strong>Status de dependencias:</strong></div>';
                    debugLogs.innerHTML += `<div>PHP Jasper: ${status.php_jasper}</div>`;
                    debugLogs.innerHTML += `<div>JasperStarter: ${status.jasperstarter}</div>`;
                    debugLogs.innerHTML += `<div>Java: ${status.java}</div>`;
                    debugLogs.innerHTML += `<div>Java Path: ${status.java_path}</div>`;
                    debugLogs.innerHTML += `<div>Jasper JAR: ${status.jasper_jar}</div>`;
                    debugLogs.innerHTML += `<div>JDBC Dir: ${status.jdbc_dir}</div>`;
                    debugLogs.innerHTML += `<div>Archivos .jrxml: ${status.jrxml_files}</div>`;
                    
                    // Mostrar logs de Laravel
                    if (status.recent_logs && status.recent_logs.length > 0) {
                        debugLogs.innerHTML += '<div><strong>Últimos logs de Laravel:</strong></div>';
                        status.recent_logs.forEach(log => {
                            debugLogs.innerHTML += `<div>[LOG] ${log}</div>`;
                        });
                    } else {
                        debugLogs.innerHTML += '<div>No hay logs recientes.</div>';
                    }
                    debugLogs.scrollTop = debugLogs.scrollHeight;
                } catch (e) {
                    debugLogs.innerHTML += `<div>[ERROR] No se pudieron cargar datos: ${e.message}</div>`;
                }
            }
        });
    </script>
</body>
</html>
