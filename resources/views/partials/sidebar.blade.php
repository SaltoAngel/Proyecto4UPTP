{{--
    Partial: Sidebar (Navegación lateral)
    Contiene enlaces principales y un panel de debug minimal.
--}}

@php
    $proveedoresActivos = \App\Models\Proveedor::where('estado', 'activo')->count();
    $recepcionesPendientes = \App\Models\Recepcion::where('estado', 'pendiente')->count();
@endphp

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <style>
        /* Estilo unificado para iconos y enlaces */
        #sidenav-main .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
        }
        #sidenav-main .nav-link .material-icons {
            font-size: 20px;
            width: 24px;
            text-align: center;
            color: #4a5568;
            opacity: 0.9;
        }
        #sidenav-main .nav-link.active .material-icons {
            color: rgb(0,165,79);
            opacity: 1;
        }
        #sidenav-main .nav-link .badge { margin-left: auto; }
        #sidenav-main .section-title {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0 14px;
        }
        #sidenav-main .section-title i {
            font-size: 18px;
            color: #6c757d;
        }
        #sidenav-main .icon.icon-shape {
            width: 34px;
            height: 34px;
        }
    </style>
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href="{{ route('dashboard') }}" aria-label="Ir al dashboard">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo Proyecto UPT" style="max-height:60px; margin-left:40px;" />
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main" style="width: 100%; overflow: auto;">

    <!-- SECCIÓN 1: MONITOREO -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
               href="{{ route('dashboard') }}">
                <i class="material-icons opacity-10">dashboard</i>
                <span class="nav-link-text">Dashboard</span>
                @if($recepcionesPendientes > 0)
                <span class="badge bg-danger">{{ $recepcionesPendientes }}</span>
                @endif
            </a>
        </li>
    </ul>

    <!-- SECCIÓN 2: MAESTROS -->
    <div class="px-3 mt-3">
        <h6 class="text-uppercase text-xs font-weight-bolder opacity-6 mb-2 section-title">
            <i class="material-icons me-1">folder_special</i>
            <span>Maestros</span>
        </h6>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.personas.*') ? 'active' : '' }}" 
               href="{{ route('dashboard.personas.index') }}">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">person</i>
                </div>
                <span class="nav-link-text">Personas</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.proveedores.*') ? 'active' : '' }}" 
               href="{{ route('dashboard.proveedores.index') }}">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">local_shipping</i>
                </div>
                <span class="nav-link-text">Proveedores</span>
            </a>
        </li>
        {{-- NUEVO: Roles y Permisos --}}
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.roles.*') ? 'active' : '' }}" 
               href="{{ route('dashboard.roles.index') }}">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">local_shipping</i>
                </div>
                <span class="nav-link-text">Roles</span>
            </a>
        </li>
             
    </ul>

    <!-- SECCIÓN 3: NUTRICIÓN ANIMAL -->
    <div class="px-3 mt-3">
        <h6 class="text-uppercase text-xs font-weight-bolder opacity-6 mb-2 section-title">
            <i class="material-icons me-1">pets</i>
            <span>Nutrición Animal</span>
        </h6>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.animales.*') ? 'active' : '' }}" 
               href="{{ route('dashboard.animales.index') }}">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">egg</i>
                </div>
                <span class="nav-link-text">Animales</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.materia-prima.*') ? 'active' : '' }}" 
               href="#">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">inventory</i>
                </div>
                <span class="nav-link-text">Materia Prima</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.formulas.*') ? 'active' : '' }}" 
               href="#">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">science</i>
                </div>
                <span class="nav-link-text">Fórmulas</span>
            </a>
        </li>
    </ul>

    <!-- SECCIÓN 4: OPERACIONES -->
    <div class="px-3 mt-3">
        <h6 class="text-uppercase text-xs font-weight-bolder opacity-6 mb-2 section-title">
            <i class="material-icons me-1">construction</i>
            <span>Operaciones</span>
        </h6>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.recepciones.*') ? 'active' : '' }}" 
               href="{{ route('dashboard.recepciones.index') }}">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">receipt</i>
                </div>
                <span class="nav-link-text">Recepción</span>
                @if($recepcionesPendientes > 0)
                <span class="badge bg-warning">{{ $recepcionesPendientes }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.planificacion.*') ? 'active' : '' }}" 
               href="#">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">calendar_today</i>
                </div>
                <span class="nav-link-text">Planificación</span>
            </a>
        </li>
    </ul>

    <!-- SECCIÓN 5: MAQUINARIA -->
    <div class="px-3 mt-3">
        <h6 class="text-uppercase text-xs font-weight-bolder opacity-6 mb-2 section-title">
            <i class="material-icons me-1">precision_manufacturing</i>
            <span>Maquinaria</span>
        </h6>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.maquinaria.*') ? 'active' : '' }}" 
               href="#">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">agriculture</i>
                </div>
                <span class="nav-link-text">Equipos</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.mantenimientos.*') ? 'active' : '' }}" 
               href="#">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">build_circle</i>
                </div>
                <span class="nav-link-text">Mantenimientos</span>
            </a>
        </li>
    </ul>

    <!-- SECCIÓN 5: AUDITORÍA -->
    <div class="px-3 mt-3">
        <h6 class="text-uppercase text-xs font-weight-bolder opacity-6 mb-2 section-title">
            <i class="material-icons me-1">security</i>
            <span>Auditoría</span>
        </h6>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.bitacora.*') ? 'active' : '' }}" 
               href="{{ route('dashboard.bitacora.index') }}">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">history</i>
                </div>
                <span class="nav-link-text">Bitácora</span>
            </a>
        </li>
    </ul>

    <!-- SECCIÓN 6: TÉCNICO -->
    <div class="px-3 mt-3">
        <h6 class="text-uppercase text-xs font-weight-bolder opacity-6 mb-2 section-title">
            <i class="material-icons me-1">settings</i>
            <span>Técnico</span>
        </h6>
    </div>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white {{ request()->routeIs('dashboard.reportes.*') ? 'active' : '' }}" 
               href="#">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">assessment</i>
                </div>
                <span class="nav-link-text">Reportes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->get('debug') ? 'active' : '' }}" 
               data-bs-toggle="collapse" href="#debug-sidebar-panel">
                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">bug_report</i>
                </div>
                <span class="nav-link-text">Debug</span>
            </a>
        </li>
        <a href="{{ url('/generate-dictionary') }}" class="btn btn-primary">
            <i class="fas fa-file-word"></i> Generar Diccionario Word
        </a>
    </ul>
    </div>
</aside>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    try {
        const list = document.getElementById('sidebar-debug-scripts');
        if (!list) return;

        // Cargar scripts
        const scripts = Array.from(document.scripts).map(s => s.src).filter(Boolean);
        const unique = Array.from(new Set(scripts));
        let items = '';
        if (unique.length === 0) {
            items += '<li class="text-muted">No hay scripts con src cargados.</li>';
        } else {
            items += unique.map(src => {
                const cleanSrc = src.split('#')[0];
                const name = cleanSrc.split('/').pop().split('?')[0] || cleanSrc;
                return `<li class="d-flex align-items-center gap-2 mb-1">
                            <i class="material-icons" style="font-size:16px;">insert_link</i>
                            <span title="${cleanSrc}">${name}</span>
                        </li>`;
            }).join('');
        }

        // Cargar status de debug
        try {
            const response = await fetch('/dashboard/debug-status');
            const status = await response.json();
            items += '<li class="mt-2"><strong>Dependencias:</strong></li>';
            items += `<li class="d-flex align-items-center gap-2 mb-1">
                        <i class="material-icons" style="font-size:16px;">code</i>
                        PHP Jasper: ${status.php_jasper}
                      </li>`;
            items += `<li class="d-flex align-items-center gap-2 mb-1">
                        <i class="material-icons" style="font-size:16px;">build</i>
                        JasperStarter: ${status.jasperstarter}
                      </li>`;
            items += `<li class="d-flex align-items-center gap-2 mb-1">
                        <i class="material-icons" style="font-size:16px;">memory</i>
                        Java: ${status.java}
                      </li>`;
            items += `<li class="d-flex align-items-center gap-2 mb-1">
                        <i class="material-icons" style="font-size:16px;">description</i>
                        Archivos .jrxml: ${status.jrxml_files}
                      </li>`;
        } catch (e) {
            items += '<li class="text-danger">Error al cargar status de debug.</li>';
        }

        list.innerHTML = items;
    } catch (e) {
        console.error('Sidebar debug error:', e);
    }
});
</script>
@endpush
