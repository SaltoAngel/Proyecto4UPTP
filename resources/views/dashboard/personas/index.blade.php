<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modulo de Personas - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>
        .badge-CREATE { background-color: #28a745; }
        .badge-UPDATE { background-color: #ffc107; color: #000; }
        .badge-DELETE { background-color: #dc3545; }
        .badge-LOGIN { background-color: #17a2b8; }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
        #personaModal .modal-content {
            border-radius: 0.5rem;
        }
        #personaModal .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        #personaModal .modal-body {
            background-color: #f8f9fa;
            padding: 2rem;
        }
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
    <div class="container mt-5">
        <h2 class="mb-4">Módulo de Personas</h2>
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personas as $persona)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{ $persona->nombres }} {{ $persona->apellidos }}</td>
                    <td>{{ $persona->tipo_documento }}-{{ $persona->documento }}</td>
                    <td>{{ $persona->telefono }}</td>
                    <td>{{ $persona->email }}</td>
                    <td>{{ $persona->direccion }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#personaModal" data-nombre="{{ $persona->nombres }} {{ $persona->apellidos }}" data-documento="{{ $persona->tipo_documento }}-{{ $persona->documento }}" data-telefono="{{ $persona->telefono }}" data-email="{{ $persona->email }}" data-direccion="{{ $persona->direccion }}"><i class="fas fa-eye"></i></button>
                            <form action="" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta persona?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" tabindex="-1"  id="personaModal" aria-labelledby="personaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="personaModalLabel">Detalles de la Persona</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="persona-details">
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var personaModal = document.getElementById('personaModal');
            personaModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var nombre = button.getAttribute('data-nombre');
                var documento = button.getAttribute('data-documento');
                var telefono = button.getAttribute('data-telefono');
                var email = button.getAttribute('data-email');
                var direccion = button.getAttribute('data-direccion');

                var modalTitle = personaModal.querySelector('.modal-title');
                var personaDetails = personaModal.querySelector('#persona-details');

                modalTitle.innerHTML = `<i class="fas fa-user-circle me-2"></i> Detalles de ${nombre}`;
                personaDetails.innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted"><i class="fas fa-user me-2"></i>Nombre Completo</h6>
                                <p class="lead">${nombre}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted"><i class="fas fa-id-card me-2"></i>Documento</h6>
                                <p class="lead">${documento}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted"><i class="fas fa-phone me-2"></i>Teléfono</h6>
                                <p class="lead">${telefono}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="text-muted"><i class="fas fa-envelope me-2"></i>Correo Electrónico</h6>
                                <p class="lead">${email}</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <h6 class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Dirección</h6>
                        <p class="lead">${direccion}</p>
                    </div>
                `;
            });
        });
    </script>
</body>
