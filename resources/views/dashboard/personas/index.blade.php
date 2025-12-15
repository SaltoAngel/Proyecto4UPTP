<!-- resources/views/dashboard/personas/index.blade.php -->
@extends('layouts.dashboard')

@section('title', 'Personas - ' . config('app.name'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">
                    <i class="fas fa-users me-2 text-success"></i>Módulo de Personas
                </h2>
                <p class="text-muted mb-0">Gestión de personas registradas en el sistema</p>
            </div>
            <div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearPersonaModal">
                    <i class="fas fa-user-plus me-2"></i>Nueva Persona
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('dashboard.personas.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Buscar por nombre, documento o email..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-success" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('dashboard.personas.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover" id="tablaPersonas">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Codigo</th>
                        <th>Nombre/Razón Social</th>
                        <th>Documento</th>
                        <th>Contacto</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personas as $persona)
                    <tr>
                        <td>{{ $loop->iteration + ($personas->currentPage() - 1) * $personas->perPage() }}</td>
                        <td>{{ $persona->codigo }}</td>
                        <td>
                            <strong>
                            @if($persona->tipo === 'juridica')
                                {{ $persona->razon_social ?: $persona->nombre_comercial }}
                            @else
                                {{ trim($persona->nombres . ' ' . $persona->apellidos) }}
                            @endif
                        </strong>
                            @if($persona->email)
                                <br><small class="text-muted">{{ $persona->email }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $persona->tipo_documento }}-{{ $persona->documento }}
                        </td>
                        <td>
                            @if($persona->telefono)
                                <div></i>{{ $persona->telefono }}</div>
                            @endif
                            @if($persona->direccion)
                                <div>{{ Str::limit($persona->direccion, 30) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $persona->tipo == 'natural' ? 'primary' : 'warning' }}">
                                {{ $persona->tipo == 'natural' ? 'Natural' : 'Jurídica' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $persona->activo ? 'activo' : 'inactivo' }}">
                                {{ $persona->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-info" data-bs-toggle="modal" 
                                        data-bs-target="#verPersonaModal" 
                                        data-persona="{{ json_encode($persona) }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <button class="btn btn-warning" data-bs-toggle="modal" 
                                        data-bs-target="#editarPersonaModal" 
                                        data-persona="{{ json_encode($persona) }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                <form action="{{ route('dashboard.personas.destroy', $persona) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('¿Está seguro de eliminar esta persona?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-3"></i>
                            <p>No se encontraron personas registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <!-- Paginación -->
            @if($personas->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $personas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modales -->
@include('dashboard.personas.modals.crear')
@include('dashboard.personas.modals.ver')
@endsection

@push('scripts')
<script>
$(document).ready(function() {

    $('#tablaPersonas').DataTable({
        responsive: true,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
        },
        order: [[1, 'asc']], // Ordenar por nombre ascendente
        columnDefs: [              
            { orderable: false, targets: 6 } // Deshabilitar ordenación en la columna de acciones
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
        '<"row"<"col-sm-12"tr>>' +
        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]]   
    });
    // Modal para ver persona
$('#verPersonaModal').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget);
    
    try {
        // Obtener y parsear los datos de la persona
        const personaData = button.data('persona');
        let persona;
        
        if (typeof personaData === 'string') {
            persona = JSON.parse(personaData);
        } else {
            persona = personaData;
        }
        
        const modal = $(this);
        
        // Determinar el nombre completo
        let nombreCompleto = '';
        if (persona.tipo === 'juridica') {
            nombreCompleto = persona.razon_social || persona.nombre_comercial || 'Empresa sin nombre';
        } else {
            nombreCompleto = (persona.nombres || '') + ' ' + (persona.apellidos || '');
            nombreCompleto = nombreCompleto.trim() || 'Persona sin nombre';
        }
        
        // Actualizar el título del modal
        modal.find('.modal-title').text('Detalles: ' + nombreCompleto);
        
        // MOSTRAR TODOS LOS CAMPOS DE LA TABLA
        
        // 1. Información Básica
        modal.find('#modalCodigo').text(persona.codigo || 'No asignado');
        modal.find('#modalTipo').text(persona.tipo === 'natural' ? 'Persona Natural' : 'Persona Jurídica');
        modal.find('#modalEstadoRegistro').html(
            persona.activo ? 
            '<span class="badge bg-success">Activo</span>' : 
            '<span class="badge bg-danger">Inactivo</span>'
        );
        
        // 2. Información Personal/Nombre
        modal.find('#modalNombreCompleto').text(nombreCompleto);
        
        if (persona.tipo === 'natural') {
            modal.find('#modalNombres').text(persona.nombres || 'No especificado');
            modal.find('#modalApellidos').text(persona.apellidos || 'No especificado');
            modal.find('#modalRazonSocial').text('N/A');
            modal.find('#modalNombreComercial').text('N/A');
        } else {
            modal.find('#modalNombres').text('N/A');
            modal.find('#modalApellidos').text('N/A');
            modal.find('#modalRazonSocial').text(persona.razon_social || 'No especificado');
            modal.find('#modalNombreComercial').text(persona.nombre_comercial || 'No especificado');
        }
        
        // 3. Documento e Identificación
        modal.find('#modalTipoDocumento').text(persona.tipo_documento || 'No especificado');
        modal.find('#modalDocumento').text(persona.documento || 'No especificado');
        
        // 4. Información de Contacto
        modal.find('#modalEmail').text(persona.email || 'No registrado');
        modal.find('#modalTelefono').text(persona.telefono || 'No registrado');
        modal.find('#modalTelefonoAlternativo').text(persona.telefono_alternativo || 'No registrado');
        
        // 5. Ubicación
        modal.find('#modalDireccion').text(persona.direccion || 'No registrada');
        modal.find('#modalEstado').text(persona.estado || 'No especificado');
        modal.find('#modalCiudad').text(persona.ciudad || 'No especificado');
        
        // 6. Fechas y Auditoría
        if (persona.created_at) {
            modal.find('#modalFechaRegistro').text(
                new Date(persona.created_at).toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })
            );
        }
        
        if (persona.updated_at) {
            modal.find('#modalFechaActualizacion').text(
                new Date(persona.updated_at).toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })
            );
        }
        
        if (persona.deleted_at) {
            modal.find('#modalFechaEliminacion').text(
                new Date(persona.deleted_at).toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                })
            );
            modal.find('#modalFechaEliminacion').addClass('text-danger');
        } else {
            modal.find('#modalFechaEliminacion').text('No eliminado');
        }
        
    } catch (error) {
        console.error('Error al procesar datos de persona:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudieron cargar los datos de la persona'
        });
    }
});
    // Modal para editar persona
    $('#editarPersonaModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const persona = JSON.parse(button.data('persona'));
        const modal = $(this);
        
        // Llenar formulario
        modal.find('form').attr('action', '/dashboard/personas/' + persona.id);
        modal.find('input[name="_method"]').val('PUT');
        modal.find('#editTipo').val(persona.tipo);
        modal.find('#editNombres').val(persona.nombres);
        modal.find('#editApellidos').val(persona.apellidos);
        modal.find('#editRazonSocial').val(persona.razon_social);
        modal.find('#editNombreComercial').val(persona.nombre_comercial);
        modal.find('#editTipoDocumento').val(persona.tipo_documento);
        modal.find('#editDocumento').val(persona.documento);
        modal.find('#editDireccion').val(persona.direccion);
        modal.find('#editEstadoGeografico').val(persona.estado_geografico);
        modal.find('#editCiudad').val(persona.ciudad);
        modal.find('#editTelefono').val(persona.telefono);
        modal.find('#editTelefonoAlternativo').val(persona.telefono_alternativo);
        modal.find('#editEmail').val(persona.email);
        modal.find('#editActivo').prop('checked', persona.activo);
        
        // Mostrar/ocultar campos según tipo
        toggleCamposPorTipo(persona.tipo);
    });
    
    // Cambiar campos según tipo de persona
    $('input[name="tipo"]').change(function() {
        toggleCamposPorTipo($(this).val());
    });
    
    function toggleCamposPorTipo(tipo) {
        if (tipo === 'natural') {
            $('.campo-natural').show();
            $('.campo-juridica').hide();
            $('#nombres, #apellidos').prop('required', true);
            $('#razon_social').prop('required', false);
        } else {
            $('.campo-natural').hide();
            $('.campo-juridica').show();
            $('#nombres, #apellidos').prop('required', false);
            $('#razon_social').prop('required', true);
        }
    }
    
    // Enviar formulario con AJAX
    $('#formCrearPersona').submit(function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                form.find('button[type="submit"]').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#crearPersonaModal').modal('hide');
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                form.find('button[type="submit"]').prop('disabled', false)
                    .html('<i class="fas fa-save me-2"></i>Guardar Persona');
                    
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '<ul>';
                    
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    
                    errorHtml += '</ul>';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        html: errorHtml
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la solicitud'
                    });
                }
            }
        });
    });
});
</script>
@endpush