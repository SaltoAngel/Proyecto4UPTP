{{--
  Partial: Header (Navbar principal)
  Contiene:
    - Título de la vista (yield title)
    - Campana de notificaciones (dropdown placeholder)
    - Menú de usuario con acceso a Configuración y Logout (POST)
--}}
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <h6 class="font-weight-bolder mb-0">@yield('title', 'Dashboard')</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Notificaciones: dropdown con placeholder -->
        <li class="nav-item dropdown me-3">
          <a class="nav-link p-0" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notificaciones">
            <i class="material-icons">notifications</i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="notifDropdown" style="min-width: 260px;">
            <li class="px-2 py-1 text-sm text-muted">No hay notificaciones</li>
          </ul>
        </li>
        <!-- Usuario: nombre y menú (configuración + logout) -->
        <li class="nav-item dropdown">
          <a class="nav-link d-flex align-items-center p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="material-icons me-1">person</i>
            <span class="d-sm-inline d-none text-muted">
              {{ auth()->user()->persona->nombre_completo ?? (auth()->user()->name ?? 'Usuario') }}
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item" href="{{ route('dashboard.configuracion') }}">
                <i class="material-icons me-2" style="font-size:18px">settings</i> Configuración
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                <i class="material-icons me-2" style="font-size:18px">logout</i> Cerrar sesión
              </a>
              <form id="header-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
