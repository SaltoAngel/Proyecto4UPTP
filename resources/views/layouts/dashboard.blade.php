<!-- resources/views/layouts/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    {{-- Iconografía y vendors externos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Assets compilados con Vite (incluye CoreUI) --}}
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])

    @stack('styles')
    <style>
        /* Desktop: animación uniforme entre sidebar y contenido */
        @media (min-width: 992px) {
            :root {
                --sidebar-width: 16rem;
                --sidebar-transition: 360ms ease-in-out;
            }
            .sidebar { transition: transform var(--sidebar-transition); will-change: transform; }
            .wrapper { transition: margin-left var(--sidebar-transition); will-change: margin-left; }
            body:not(.sidebar-collapsed) .wrapper { margin-left: var(--sidebar-width); }
            body.sidebar-collapsed .wrapper { margin-left: 0; }
        }

        /* Respeto a usuarios con reducción de movimiento */
        @media (prefers-reduced-motion: reduce) {
            .sidebar, .wrapper { transition: none !important; }
        }
    </style>
</head>
<body class="bg-light">
    @include('partials.sidebar')

    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <header class="header header-sticky mb-4">
            <div class="container-fluid border-bottom d-flex align-items-center">
                <button class="header-toggler px-md-0 me-3" type="button">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="flex-grow-1"></div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="fa fa-user me-1"></i>{{ auth()->user()->persona->nombre_completo ?? auth()->user()->name }}
                    </span>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <button class="btn btn-outline-secondary btn-sm" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt me-1"></i>Salir
                    </button>
                </div>
            </div>
            <div class="container-fluid bg-white py-2 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h5>
                    <div class="ms-auto">
                        @hasSection('breadcrumbs')
                            @yield('breadcrumbs')
                        @else
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb my-0 small">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                                    <li class="breadcrumb-item active"><span>@yield('title', 'Dashboard')</span></li>
                                </ol>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-coreui-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>

        <footer class="footer px-4 py-3 bg-white border-top">
            <div class="ms-auto">{{ config('app.name') }} &copy; {{ now()->year }}</div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        });
    </script>

    @stack('scripts')
</body>
</html>