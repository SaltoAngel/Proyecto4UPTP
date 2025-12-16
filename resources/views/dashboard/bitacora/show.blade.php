<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Bitácora - {{ $bitacora->codigo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('bitacora.index') }}">
                    <i class="fas fa-arrow-left me-1"></i>Volver a Bitácora
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Detalle del Registro
                    <code class="ms-2">{{ $bitacora->codigo }}</code>
                </h4>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-info-circle me-2"></i>Información General</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Usuario:</strong></td>
                                <td>{{ $bitacora->user->name ?? 'Usuario eliminado' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Módulo:</strong></td>
                                <td><span class="badge bg-secondary">{{ $bitacora->modulo }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Acción:</strong></td>
                                <td><span class="badge bg-primary">{{ $bitacora->accion }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha:</strong></td>
                                <td>{{ $bitacora->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>IP:</strong></td>
                                <td><code>{{ $bitacora->ip_address }}</code></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5><i class="fas fa-browser me-2"></i>Información Técnica</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>User Agent:</strong></td>
                                <td><small>{{ Str::limit($bitacora->user_agent, 50) }}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-12">
                        <h5><i class="fas fa-clipboard-list me-2"></i>Detalle de la Acción</h5>
                        <div class="alert alert-info">
                            {{ $bitacora->detalle }}
                        </div>
                    </div>
                </div>

                @if($bitacora->datos_anteriores || $bitacora->datos_nuevos)
                <hr>
                <div class="row">
                    @if($bitacora->datos_anteriores)
                    <div class="col-md-6">
                        <h5><i class="fas fa-history me-2"></i>Datos Anteriores</h5>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($bitacora->datos_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                    @endif
                    
                    @if($bitacora->datos_nuevos)
                    <div class="col-md-6">
                        <h5><i class="fas fa-plus-circle me-2"></i>Datos Nuevos</h5>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($bitacora->datos_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>