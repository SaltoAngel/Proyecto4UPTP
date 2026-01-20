@extends('layouts.material')

@section('title', 'Gestión de Usuarios')

@push('styles')
<style>
    .acciones-usuario .btn { display: inline-flex; align-items: center; justify-content: center; }
    .acciones-usuario .btn .material-icons { font-size: 18px; line-height: 1; }
    /* Asegura unión visual cuando hay botones y formularios mezclados */
    .acciones-usuario .btn + .btn { margin-left: -1px; }
    .acciones-usuario .btn + form .btn, .acciones-usuario form + .btn { margin-left: -1px; }
    .acciones-usuario form { display: inline-block; margin: 0; }
    .acciones-usuario .btn { border-radius: 0; }
    .acciones-usuario .btn:first-child { border-top-left-radius: .2rem; border-bottom-left-radius: .2rem; }
    .acciones-usuario .btn:last-child { border-top-right-radius: .2rem; border-bottom-right-radius: .2rem; }
    
    .avatar-inicial {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        font-size: 14px;
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">
                    <i class="material-icons me-2 text-primary">people</i>Gestión de Usuarios
                </h2>
                <p class="text-muted mb-0">Administre los usuarios del sistema</p>
            </div>
            <div>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="material-icons me-2">person_add</i>Nuevo Usuario
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tablaUsuarios">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>Documento</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr data-user-id="{{ $user->id }}">
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-inicial bg-primary me-3">
                                    {{ strtoupper(substr($user->persona->nombres ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->persona->nombres ?? 'N/A' }} {{ $user->persona->apellidos ?? '' }}</strong>
                                    @if($user->username)
                                        <br><small class="text-muted">@ {{ $user->username }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $user->persona->tipo_documento ?? 'V' }}-{{ $user->persona->documento ?? 'N/A' }}
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->roles->first() ? 'primary' : 'secondary' }}">
                                {{ $user->roles->first()->name ?? 'Sin rol' }}
                            </span>
                        </td>
                        <td>
                            @if($user->status == 'activo')
                                <span class="badge bg-success">Activo</span>
                            @elseif($user->status == 'pendiente')
                                <span class="badge bg-warning">Pendiente</span>
                            @else
                                <span class="badge bg-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm acciones-usuario">
                                <a href="{{ route('users.show', $user->id) }}" 
                                   class="btn btn-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalles">
                                    <i class="material-icons">visibility</i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" 
                                   class="btn btn-warning" 
                                   data-bs-toggle="tooltip" 
                                   title="Editar">
                                    <i class="material-icons">edit</i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger" 
                                            data-bs-toggle="tooltip" 
                                            title="Eliminar"
                                            onclick="return confirm('¿Está seguro de eliminar este usuario?')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="material-icons" style="font-size:2rem;">person_off</i>
                            <p>No se encontraron usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Usuarios JS inicializado');

    // Configurar DataTable para usuarios
    const tabla = $('#tablaUsuarios').DataTable({
        responsive: true,
        "language": {
            "url": "{{ asset('datatables-i18n-es.json') }}"
        },
        order: [[5, 'asc'], [1, 'asc']], // Ordenar por estado y luego nombre
        columnDefs: [
            { orderable: false, targets: 0 }, // La numeración
            { orderable: false, targets: 7 } // Columna de acciones
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        pageLength: 10
    });

    // Inicializar tooltips de Bootstrap
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Recalcular numeración cuando cambie el orden o la búsqueda en DataTable
    tabla.on('order.dt search.dt draw.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
            this.data(i++);
        });
    });
});
</script>
@endpush