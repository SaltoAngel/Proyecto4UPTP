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
    <!-- Botón para abrir modal -->
    <button type="button" 
            class="btn btn-success" 
            data-bs-toggle="modal" 
            data-bs-target="#crearUsuarioModal">
        <i class="material-icons me-2">person_add</i>Nuevo Usuario
    </button>
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
                                <span class="badge bg-success estado-usuario">Activo</span>
                            @elseif($user->status == 'pendiente')
                                <span class="badge bg-warning estado-usuario">Pendiente</span>
                            @else
                                <span class="badge bg-danger estado-usuario">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm acciones-usuario" data-user-id="{{ $user->id }}">
                                <a href="{{ route('users.show', $user->id) }}" 
                                   class="btn btn-info" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalles">
                                    <i class="material-icons">visibility</i>
                                </a>
                <!-- En tu vista user.blade.php, modifica el botón de editar dentro del foreach: -->
<button class="btn btn-sm btn-warning" 
        data-bs-toggle="modal" 
        data-bs-target="#editarUsuarioModal"
        data-id="{{ $user->id }}"
        data-persona-info="{{ optional($user->persona)->nombres ?? 'N/A' }} {{ optional($user->persona)->apellidos ?? '' }} - {{ optional($user->persona)->documento ?? 'N/A' }}"
        data-email="{{ $user->email }}"
        data-status="{{ $user->status }}"
        data-created="{{ $user->created_at->format('d/m/Y H:i') }}"
        data-updated="{{ $user->updated_at->format('d/m/Y H:i') }}"
        data-last-login="{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}"
        data-rol-actual="{{ $user->roles->first()->name ?? '' }}"
        data-roles="{{ json_encode($roles) }}">
    <i class="material-icons">edit</i>
</button>

                                
                                {{-- Botones de activar/desactivar para todos los estados --}}
                                @if($user->status == 'activo')
                                    <button type="button" 
                                            class="btn btn-danger btn-desactivar-usuario" 
                                            data-bs-toggle="tooltip" 
                                            title="Desactivar usuario"
                                            data-id="{{ $user->id }}"
                                            data-status="{{ $user->status }}"
                                            data-url="{{ route('users.deactivate', $user->id) }}">
                                        <i class="material-icons">block</i>
                                    </button>
                                @elseif($user->status == 'inactivo')
                                    <button type="button" 
                                            class="btn btn-success btn-activar-usuario" 
                                            data-bs-toggle="tooltip" 
                                            title="Activar usuario"
                                            data-id="{{ $user->id }}"
                                            data-status="{{ $user->status }}"
                                            data-url="{{ route('users.activate', $user->id) }}">
                                        <i class="material-icons">check_circle</i>
                                    </button>
                                @elseif($user->status == 'pendiente')
                                    <button type="button" 
                                            class="btn btn-danger btn-desactivar-usuario" 
                                            data-bs-toggle="tooltip" 
                                            title="Desactivar usuario"
                                            data-id="{{ $user->id }}"
                                            data-status="{{ $user->status }}"
                                            data-url="{{ route('users.deactivate', $user->id) }}">
                                        <i class="material-icons">block</i>
                                    </button>
                                @endif
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

<!-- Incluir el modal -->
@include('users.modal-create')
@include('users.edit')


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

    // Asegurar token CSRF en todas las peticiones AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Desactivar usuario
    $(document).on('click', '.btn-desactivar-usuario', function(e) {
        e.preventDefault();
        const boton = $(this);
        const url = boton.data('url');
        const id = boton.data('id');
        const status = boton.data('status'); // Obtener el estado actual
        const fila = boton.closest('tr');
        
        // Si el usuario está en estado "pendiente", mostrar mensaje especial
        if (status === 'pendiente') {
            Swal.fire({
                title: 'Usuario pendiente',
                html: 'Este usuario no puede ser desactivado porque:<br>' +
                      '• <strong>No ha cambiado su contraseña por primera vez</strong><br>' +
                      '• <strong>No ha iniciado sesión en el sistema</strong><br><br>' +
                      'Solo podrá ser desactivado cuando complete su primer inicio de sesión ' +
                      'y cambie su contraseña, cambiando su estado a "Activo".',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        // Para usuarios activos, mostrar confirmación normal
        Swal.fire({
            title: 'Desactivar usuario',
            text: 'El usuario no podrá acceder al sistema hasta que sea reactivado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: { _method: 'PUT' },
                success: function(response) {
                    if (!response || response.success === false) {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Error', 
                            text: response?.message || 'No se pudo desactivar el usuario' 
                        });
                        return;
                    }
                    
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Usuario desactivado', 
                        text: response.message || 'Usuario desactivado correctamente', 
                        timer: 1500, 
                        showConfirmButton: false 
                    });
                    
                    // Actualizar estado visual
                    fila.find('.estado-usuario')
                        .removeClass('bg-success')
                        .addClass('bg-danger')
                        .text('Inactivo');
                    
                    // Actualizar el estado en el botón
                    boton.data('status', 'inactivo');
                    
                    // Cambiar el botón de desactivar por botón de activar
                    const activateUrl = "{{ route('users.activate', ':id') }}".replace(':id', id);
                    boton.replaceWith(
                        `<button type="button" class="btn btn-success btn-activar-usuario" 
                                data-bs-toggle="tooltip" title="Activar usuario"
                                data-id="${id}" data-status="inactivo" data-url="${activateUrl}">
                            <i class="material-icons">check_circle</i>
                        </button>`
                    );
                    
                    // Actualizar DataTable
                    tabla.row(fila).invalidate().draw(false);
                },
                error: function() {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error', 
                        text: 'No se pudo desactivar el usuario' 
                    });
                }
            });
        });
    });

    // Activar usuario
    $(document).on('click', '.btn-activar-usuario', function(e) {
        e.preventDefault();
        const boton = $(this);
        const url = boton.data('url');
        const id = boton.data('id');
        const status = boton.data('status');
        const fila = boton.closest('tr');

        Swal.fire({
            title: 'Activar usuario',
            text: 'El usuario podrá acceder al sistema nuevamente.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, activar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: { _method: 'PUT' },
                success: function(response) {
                    if (!response || response.success === false) {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Error', 
                            text: response?.message || 'No se pudo activar el usuario' 
                        });
                        return;
                    }
                    
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Usuario activado', 
                        text: response.message || 'Usuario activado correctamente', 
                        timer: 1500, 
                        showConfirmButton: false 
                    });
                    
                    // Actualizar estado visual
                    fila.find('.estado-usuario')
                        .removeClass('bg-danger')
                        .addClass('bg-success')
                        .text('Activo');
                    
                    // Actualizar el estado en el botón
                    boton.data('status', 'activo');
                    
                    // Cambiar el botón de activar por botón de desactivar
                    const deactivateUrl = "{{ route('users.deactivate', ':id') }}".replace(':id', id);
                    boton.replaceWith(
                        `<button type="button" class="btn btn-danger btn-desactivar-usuario" 
                                data-bs-toggle="tooltip" title="Desactivar usuario"
                                data-id="${id}" data-status="activo" data-url="${deactivateUrl}">
                            <i class="material-icons">block</i>
                        </button>`
                    );
                    
                    // Actualizar DataTable
                    tabla.row(fila).invalidate().draw(false);
                },
                error: function(xhr) {
                    const msg = xhr?.responseJSON?.message || 'No se pudo activar el usuario';
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error', 
                        text: msg 
                    });
                }
            });
        });
    });
});
</script>
@endpush
