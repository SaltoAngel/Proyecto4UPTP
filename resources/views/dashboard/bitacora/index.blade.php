<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Cambios - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .badge-CREATE { background-color: #28a745; }
        .badge-UPDATE { background-color: #ffc107; color: #000; }
        .badge-DELETE { background-color: #dc3545; }
        .badge-LOGIN { background-color: #17a2b8; }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-history me-2"></i>Bitácora de Cambios
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filtros -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Módulo</label>
                                <input type="text" class="form-control" name="modulo" 
                                       value="{{ request('modulo') }}" placeholder="Buscar módulo...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="usuario" 
                                       value="{{ request('usuario') }}" placeholder="Buscar usuario...">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Desde</label>
                                <input type="date" class="form-control" name="fecha_desde" 
                                       value="{{ request('fecha_desde') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Hasta</label>
                                <input type="date" class="form-control" name="fecha_hasta" 
                                       value="{{ request('fecha_hasta') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>Filtrar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Tabla -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Código</th>
                                        <th>Usuario</th>
                                        <th>Módulo</th>
                                        <th>Acción</th>
                                        <th>Detalle</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bitacora as $registro)
                                    <tr>
                                        <td>
                                            <code>{{ $registro->codigo }}</code>
                                        </td>
                                        <td>
                                            <i class="fas fa-user me-1"></i>
                                            {{ $registro->user->name ?? 'Usuario eliminado' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $registro->modulo }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $registro->accion }}">
                                                {{ $registro->accion }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($registro->detalle, 50) }}</td>
                                        <td>
                                            <small>
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $registro->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('dashboard.bitacora.show', $registro) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">No hay registros en la bitácora</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center">
                            {{ $bitacora->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>