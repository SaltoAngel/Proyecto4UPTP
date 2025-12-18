{{--
    Partial: Sidebar (Navegación lateral)
    Contiene:
        - Enlaces a Dashboard, Personas, Proveedores y Bitácora.
        - Panel Debug plegable: lista scripts cargados (por src) para verificación.
        - Footer vacío (logout se trasladó al header).
--}}
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-white" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href="{{ route('dashboard') }}" aria-label="Ir al dashboard">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo Proyecto UPT" style="max-height:60px; margin-left:40px;" />
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('dashboard.personas.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('dashboard.personas.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">groups</i>
                    </div>
                    <span class="nav-link-text ms-1">Personas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('dashboard.proveedores.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('dashboard.proveedores.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">local_shipping</i>
                    </div>
                    <span class="nav-link-text ms-1">Proveedores</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('dashboard.bitacora.*') ? 'active bg-gradient-primary' : '' }}" href="{{ route('dashboard.bitacora.index') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">history</i>
                    </div>
                    <span class="nav-link-text ms-1">Bitácora</span>
                </a>
            </li>
        </ul>
        <!-- Debug Panel: listado de scripts cargados -->
        <div class="mt-3">
            <a class="nav-link d-flex align-items-center {{ request()->get('debug') ? 'active' : '' }}" data-bs-toggle="collapse" href="#debug-sidebar-panel" role="button" aria-expanded="false" aria-controls="debug-sidebar-panel">
                <div class="text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="material-icons opacity-10">bug_report</i>
                </div>
                <span class="nav-link-text ms-1">Debug</span>
                <i class="material-icons ms-auto">expand_more</i>
            </a>
            <div class="collapse" id="debug-sidebar-panel">
                <div class="border rounded p-2 bg-light" style="max-height: 180px; overflow:auto;">
                    <ul class="list-unstyled small mb-0" id="sidebar-debug-scripts">
                        <li class="text-muted">Cargando scripts…</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer vacío: previamente aquí estaba el botón de salir -->
    <div class="sidenav-footer position-absolute w-100 bottom-0 px-3 pb-3"></div>
</aside>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    try {
        const list = document.getElementById('sidebar-debug-scripts');
        if (!list) return;
        const scripts = Array.from(document.scripts).map(s => s.src).filter(Boolean);
        const unique = Array.from(new Set(scripts));
        if (unique.length === 0) {
            list.innerHTML = '<li class="text-muted">No hay scripts con src cargados.</li>';
            return;
        }
        const items = unique.map(src => {
            const cleanSrc = src.split('#')[0];
            const name = cleanSrc.split('/').pop().split('?')[0] || cleanSrc;
            return `<li class="d-flex align-items-center gap-2 mb-1">
                                <i class="material-icons" style="font-size:16px;">insert_link</i>
                                <span title="${cleanSrc}">${name}</span>
                            </li>`;
        }).join('');
        list.innerHTML = items;
    } catch (e) {
        console.error('Sidebar debug error:', e);
    }
});
</script>
@endpush