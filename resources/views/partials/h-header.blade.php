<header>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="{{ asset('img/logo.jpg') }}" alt="Logo de la marca">
                <h1>Nupilca C.A</h1>
            </div>
            
            <ul class="nav-links">
                <li><a href="{{ route('Homepage.index') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Inicio</a></li>
                <li><a href="{{ route('servicios') }}" class="{{ request()->routeIs('servicios') ? 'active' : '' }}">Servicios</a></li>
                <li><a href="{{ route('productos') }}" class="{{ request()->routeIs('productos') ? 'active' : '' }}">Productos</a></li>
                <li><a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'active' : '' }}">Nosotros</a></li>
                <li><a href="{{ route('contacto') }}" class="{{ request()->routeIs('contacto') ? 'active' : '' }}">Contacto</a></li>
            </ul>
            
            <div class="nav-buttons">
                <a href="#" class="btn btn-primary">Agendar pedidos</a>
                <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
            </div>
            
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
</header>