<!-- resources/views/dashboard/personas/index.blade.php -->
@extends('layouts.material')

@section('title', 'Personas - ' . config('app.name'))

@push('styles')
<style>
    .acciones-persona .btn { display: inline-flex; align-items: center; justify-content: center; }
    .acciones-persona .btn .material-icons { font-size: 18px; line-height: 1; }
    /* Asegura unión visual cuando hay botones y formularios mezclados (fallback) */
    .acciones-persona .btn + .btn { margin-left: -1px; }
    .acciones-persona .btn + form .btn, .acciones-persona form + .btn { margin-left: -1px; }
    .acciones-persona form { display: inline-block; margin: 0; }
    .acciones-persona .btn { border-radius: 0; }
    .acciones-persona .btn:first-child { border-top-left-radius: .2rem; border-bottom-left-radius: .2rem; }
    .acciones-persona .btn:last-child { border-top-right-radius: .2rem; border-bottom-right-radius: .2rem; }
}</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">
                    <i class="material-icons me-2 text-success">groups</i>Módulo de Personas
                </h2>
                <p class="text-muted mb-0">Gestión de personas registradas en el sistema</p>
            </div>
            <div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearPersonaModal">
                    <i class="material-icons me-2">person_add</i>Nueva Persona
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
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
                    <tr data-persona-id="{{ $persona->id }}">
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
                            @php $estaDeshabilitada = $persona->trashed() || !$persona->activo; @endphp
                            <span class="badge {{ $estaDeshabilitada ? 'bg-secondary' : 'bg-success' }} estado-badge">
                                {{ $estaDeshabilitada ? 'Deshabilitado' : 'Activo' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm acciones-persona" data-persona-id="{{ $persona->id }}" data-restore-url="{{ route('dashboard.personas.restore', $persona->id) }}">
                                <button class="btn btn-info" data-bs-toggle="modal" 
                                        data-bs-target="#verPersonaModal" 
                                        data-persona='@json($persona)'>
                                    <i class="material-icons">visibility</i>
                                </button>
                                
                                <button class="btn btn-warning" data-bs-toggle="modal" 
                                        data-bs-target="#editarPersonaModal" 
                                        data-persona='@json($persona)'>
                                    <i class="material-icons">edit</i>
                                </button>
                                
                                @if($estaDeshabilitada)
                                    <button type="button" class="btn btn-success btn-restaurar-persona" data-id="{{ $persona->id }}" data-url="{{ route('dashboard.personas.restore', $persona->id) }}">
                                        <i class="material-icons">restore</i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-deshabilitar-persona" data-url="{{ route('dashboard.personas.destroy', $persona) }}">
                                        <i class="material-icons">person_off</i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="material-icons" style="font-size:2rem;">groups</i>
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

{{-- <div class="d-flex justify-content-end gap-2 mb-3">
    <a class="btn btn-outline-secondary" href="{{ route('dashboard.reportes.personas', ['formato' => 'pdf']) }}" target="_blank">
        <i class="fas fa-file-pdf me-1"></i> PDF
    </a>
    <a class="btn btn-outline-success" href="{{ route('dashboard.reportes.personas', ['formato' => 'xlsx']) }}" target="_blank">
        <i class="fas fa-file-excel me-1"></i> Excel
    </a>
</div> --}}

<!-- Modales -->
@include('dashboard.personas.modals.crear')
@include('dashboard.personas.modals.ver')
@include('dashboard.personas.modals.editar')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Personas JS inicializado');

    // Asegurar token CSRF en todas las peticiones AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const tabla = $('#tablaPersonas').DataTable({
        responsive: true,
        "language": {
            "url": "{{ asset('datatables-i18n-es.json') }}"
        },
        order: [[6, 'asc'], [1, 'asc']], // Prioriza estado (habilitado primero), luego código
        columnDefs: [              
            { orderable: false, targets: 0 }, // La numeración se recalcula
            { orderable: false, targets: 7 } // Deshabilitar ordenación en la columna de acciones
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
        const leyenda = persona.tipo_documento === 'V' ? 'Venezolano' :
                persona.tipo_documento === 'E' ? 'Extranjero' :
                persona.tipo_documento === 'J' ? 'RIF' :
                persona.tipo_documento === 'G' ? 'Gubernamental' :
                persona.tipo_documento === 'P' ? 'Pasaporte' : '-';
        modal.find('#modalTipoDocLeyenda').text(leyenda);
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
        const personaData = button.data('persona');
        const persona = typeof personaData === 'string' ? JSON.parse(personaData) : personaData;
        const modal = $(this);
        
        // Llenar formulario
        modal.find('form').attr('action', '/dashboard/personas/' + persona.id);
        modal.find('input[name="_method"]').val('PUT');
        modal.find('input[name="tipo"][value="' + persona.tipo + '"]').prop('checked', true);
        modal.find('#editNombres').val(persona.nombres);
        modal.find('#editApellidos').val(persona.apellidos);
        modal.find('#editRazonSocial').val(persona.razon_social);
        modal.find('#editNombreComercial').val(persona.nombre_comercial);
        modal.find('#editTipoDocumento').val(persona.tipo_documento);
        updateTipoDocLeyenda('#editTipoDocumento', '#editTipoDocLeyenda');
        ajustarTipoDocumento(persona.tipo, '#editTipoDocumento', '#editTipoDocLeyenda');
        modal.find('#editDocumento').val(persona.documento);
        modal.find('#editDireccion').val(persona.direccion);
        modal.find('#editEstado').val(persona.estado);
        modal.find('#editCiudad').val(persona.ciudad);
        modal.find('#editTelefono').val(persona.telefono);
        modal.find('#editTelefonoAlternativo').val(persona.telefono_alternativo);
        modal.find('#editEmail').val(persona.email);
        
        toggleCamposPorTipoEdit(persona.tipo);
    });
    
    // Cambiar campos según tipo de persona
    $('#crearPersonaModal input[name="tipo"]').change(function() {
        const tipo = $(this).val();
        toggleCamposPorTipo(tipo);
        aplicarOpcionesDocumento(tipo, '#tipo_documento');
        ajustarTipoDocumento(tipo, '#tipo_documento', '#tipoDocLeyenda', true);
    });

    $('#editarPersonaModal input[name="tipo"]').change(function() {
        const tipo = $(this).val();
        toggleCamposPorTipoEdit(tipo);
        aplicarOpcionesDocumento(tipo, '#editTipoDocumento');
        ajustarTipoDocumento(tipo, '#editTipoDocumento', '#editTipoDocLeyenda', true);
    });

    $('#tipo_documento').on('change', function() {
        const tipo = $('#crearPersonaModal input[name="tipo"]:checked').val();
        ajustarTipoDocumento(tipo, '#tipo_documento', '#tipoDocLeyenda');
        if ($(this).val() === 'G' && tipo === 'juridica') {
            advertirGubernamental();
        }
    });

    $('#editTipoDocumento').on('change', function() {
        const tipo = $('#editarPersonaModal input[name="tipo"]:checked').val();
        ajustarTipoDocumento(tipo, '#editTipoDocumento', '#editTipoDocLeyenda');
        if ($(this).val() === 'G' && tipo === 'juridica') {
            advertirGubernamental();
        }
    });

    // Inicializar leyendas y tipo doc según tipo seleccionado al cargar
    const tipoCrear = $('#crearPersonaModal input[name="tipo"]:checked').val();
    aplicarOpcionesDocumento(tipoCrear, '#tipo_documento');
    ajustarTipoDocumento(tipoCrear, '#tipo_documento', '#tipoDocLeyenda', false);

    const tipoEditar = $('#editarPersonaModal input[name="tipo"]:checked').val();
    aplicarOpcionesDocumento(tipoEditar, '#editTipoDocumento');
    ajustarTipoDocumento(tipoEditar, '#editTipoDocumento', '#editTipoDocLeyenda', false);
    
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

    function toggleCamposPorTipoEdit(tipo) {
        if (tipo === 'natural') {
            $('.campo-natural-edit').show();
            $('.campo-juridica-edit').hide();
            $('#editNombres, #editApellidos').prop('required', true);
            $('#editRazonSocial').prop('required', false);
        } else {
            $('.campo-natural-edit').hide();
            $('.campo-juridica-edit').show();
            $('#editNombres, #editApellidos').prop('required', false);
            $('#editRazonSocial').prop('required', true);
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
                        timer: 1800,
                        showConfirmButton: false
                    });

                    // Agregar fila a DataTable sin recargar
                    const p = response.data;
                    const nuevoIndice = tabla.rows().count() + 1;
                    const estadoBadge = '<span class="badge bg-success estado-badge">Activo</span>';
                    const tipoBadge = '<span class="badge ' + (p.tipo === 'natural' ? 'bg-primary' : 'bg-warning') + '">' + (p.tipo === 'natural' ? 'Natural' : 'Jurídica') + '</span>';
                    const contacto = (p.telefono ? '<div>' + p.telefono + '</div>' : '') + (p.direccion ? '<div>' + p.direccion + '</div>' : '');
                    const nombre = (p.tipo === 'juridica') ? (p.razon_social || p.nombre_comercial || '') : ((p.nombres || '') + ' ' + (p.apellidos || ''));
                    const email = p.email ? '<br><small class="text-muted">' + p.email + '</small>' : '';
                    const acciones = `
                        <div class="btn-group btn-group-sm acciones-persona" data-persona-id="${p.id}" data-restore-url="/dashboard/personas/${p.id}/restore">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verPersonaModal" data-persona='${JSON.stringify(p)}'>
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarPersonaModal" data-persona='${JSON.stringify(p)}'>
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="/dashboard/personas/${p.id}" method="POST" class="d-inline form-deshabilitar-persona">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-user-slash"></i>
                                </button>
                            </form>
                        </div>`;

                    const nuevaFila = tabla.row.add([
                        nuevoIndice,
                        p.codigo || '',
                        '<strong>' + nombre + '</strong>' + email,
                        (p.tipo_documento || '') + '-' + (p.documento || ''),
                        contacto,
                        tipoBadge,
                        estadoBadge,
                        acciones
                    ]).draw(false).node();

                    $(nuevaFila).attr('data-persona-id', p.id);
                    $('#crearPersonaModal').modal('hide');
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

    // Editar persona con AJAX y SweetAlert
    $('#formEditarPersona').submit(function(e) {
        e.preventDefault();

        const form = $(this);
        const formData = form.serialize();
        const submitBtn = form.find('button[type="submit"]');

        $.ajax({
            url: form.attr('action'),
            method: 'POST', // _method en el form maneja PUT
            data: formData,
            beforeSend: function() {
                submitBtn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...');
            },
            success: function(response) {
                submitBtn.prop('disabled', false)
                    .html('<i class="fas fa-save me-2"></i>Actualizar');

                if (response.success) {
                    const p = response.data;

                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: response.message || 'Persona actualizada correctamente',
                        timer: 1800,
                        showConfirmButton: false
                    });

                    // Actualizar fila en la tabla
                    const fila = $('tr[data-persona-id="' + p.id + '"]');
                    if (fila.length) {
                        const contacto = (p.telefono ? '<div>' + p.telefono + '</div>' : '') + (p.direccion ? '<div>' + p.direccion + '</div>' : '');
                        const nombre = (p.tipo === 'juridica') ? (p.razon_social || p.nombre_comercial || '') : ((p.nombres || '') + ' ' + (p.apellidos || ''));
                        const email = p.email ? '<br><small class="text-muted">' + p.email + '</small>' : '';
                        const tipoBadge = '<span class="badge ' + (p.tipo === 'natural' ? 'bg-primary' : 'bg-warning') + '">' + (p.tipo === 'natural' ? 'Natural' : 'Jurídica') + '</span>';
                        const estadoBadge = '<span class="badge ' + (p.activo ? 'bg-success' : 'bg-secondary') + ' estado-badge">' + (p.activo ? 'Activo' : 'Deshabilitado') + '</span>';
                        const acciones = `
                            <div class="btn-group btn-group-sm acciones-persona" data-persona-id="${p.id}" data-restore-url="/dashboard/personas/${p.id}/restore">
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verPersonaModal" data-persona='${JSON.stringify(p)}'>
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarPersonaModal" data-persona='${JSON.stringify(p)}'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="/dashboard/personas/${p.id}" method="POST" class="d-inline form-deshabilitar-persona">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                </form>
                            </div>`;

                        const rowData = tabla.row(fila).data();
                        rowData[1] = p.codigo || '';
                        rowData[2] = '<strong>' + nombre + '</strong>' + email;
                        rowData[3] = (p.tipo_documento || '') + '-' + (p.documento || '');
                        rowData[4] = contacto;
                        rowData[5] = tipoBadge;
                        rowData[6] = estadoBadge;
                        rowData[7] = acciones;
                        tabla.row(fila).data(rowData).invalidate().draw(false);
                    }

                    $('#editarPersonaModal').modal('hide');
                }
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false)
                    .html('<i class="fas fa-save me-2"></i>Actualizar');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '<ul>';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    errorHtml += '</ul>';
                    Swal.fire({ icon: 'error', title: 'Error de validación', html: errorHtml });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo actualizar la persona' });
                }
            }
        });
    });

    // Recalcular numeración cuando cambie el orden o la búsqueda
    tabla.on('order.dt search.dt draw.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
            this.data(i++);
        });
    });

    function updateTipoDocLeyenda(selectId, spanId) {
        const val = $(selectId).val();
        let texto = '-';
        if (val === 'V') texto = 'Venezolano';
        if (val === 'E') texto = 'Extranjero';
        if (val === 'J') texto = 'RIF';
        if (val === 'G') texto = 'Gubernamental';
        if (val === 'P') texto = 'Pasaporte';
        $(spanId).text(texto);
    }

    function aplicarOpcionesDocumento(tipo, selectId) {
        const select = $(selectId);
        const permitir = tipo === 'juridica' ? ['J', 'G'] : ['V', 'E', 'P'];
        select.find('option').each(function() {
            const val = $(this).val();
            const visible = permitir.includes(val) || val === '';
            $(this).prop('disabled', !visible).toggle(visible);
        });
    }

    function ajustarTipoDocumento(tipo, selectId, spanId, showAlert = true) {
        const select = $(selectId);
        const valor = select.val();
        const esJuridica = tipo === 'juridica';
        const permitir = esJuridica ? ['J', 'G'] : ['V', 'E', 'P'];

        if (!permitir.includes(valor)) {
            if (esJuridica) {
                select.val('J');
            } else {
                if (showAlert && valor) {
                    advertirTipoDocumentoNatural();
                }
                select.val('V');
            }
        }
        updateTipoDocLeyenda(selectId, spanId);
    }

    // Deshabilitar (soft delete) con SweetAlert
    $(document).on('click', '.btn-deshabilitar-persona', function(e) {
        e.preventDefault();
        const boton = $(this);
        const url = boton.data('url');
        const fila = boton.closest('tr');
        const acciones = fila.find('.acciones-persona');
        const personaId = fila.data('persona-id');

        Swal.fire({
            title: 'Deshabilitar persona',
            text: 'Podrás restaurarla luego.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, deshabilitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: url,
                method: 'POST',
                data: { _method: 'DELETE' },
                success: function(response) {
                    if (!response || response.success === false) {
                        Swal.fire({ icon: 'error', title: 'Error', text: response?.message || 'No se pudo deshabilitar la persona' });
                        return;
                    }
                    Swal.fire({ icon: 'success', title: 'Deshabilitada', text: response.message || 'Persona deshabilitada', timer: 1500, showConfirmButton: false });
                    fila.find('.estado-badge').removeClass('bg-success').addClass('bg-secondary').text('Deshabilitado');
                    const restoreUrl = acciones.data('restore-url') || `/dashboard/personas/${personaId}/restore`;
                    // Reemplazar botón de deshabilitar por restaurar
                    boton.replaceWith(`<button type="button" class="btn btn-success btn-restaurar-persona" data-id="${personaId}" data-url="${restoreUrl}"><i class="material-icons">restore</i></button>`);
                    acciones.data('restore-url', restoreUrl);
                    tabla.row(fila).invalidate().draw(false);
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo deshabilitar la persona' });
                }
            });
        });
    });

    // Restaurar persona
    $(document).on('click', '.btn-restaurar-persona', function() {
        const id = $(this).data('id');
        const boton = $(this);
        const url = boton.data('url') || `/dashboard/personas/${id}/restore`;
        console.log('Restaurar click', { id, url });
        Swal.fire({
            title: 'Restaurar persona',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Restaurar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Restaurar confirm', { id, url });
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {},
                    success: function(response) {
                        console.log('Restaurar success', response);
                        if (!response || response.success === false) {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'No se pudo restaurar la persona' });
                            return;
                        }
                        Swal.fire({ icon: 'success', title: 'Restaurada', text: response.message ?? 'Persona restaurada', timer: 1500, showConfirmButton: false });
                        const fila = boton.closest('tr');
                        fila.find('.estado-badge').removeClass('bg-secondary').addClass('bg-success').text('Activo');
                        const acciones = fila.find('.acciones-persona');
                        const restoreUrl = acciones.data('restore-url') || `/dashboard/personas/${id}/restore`;
                        acciones.find('.btn-restaurar-persona').remove();
                        acciones.append(`<button type="button" class="btn btn-danger btn-deshabilitar-persona" data-url="/dashboard/personas/${id}"><i class="material-icons">person_off</i></button>`);
                        acciones.data('restore-url', restoreUrl);
                        tabla.row(fila).invalidate().draw(false);
                    },
                    error: function(xhr) {
                        console.log('Restaurar error', xhr);
                        const msg = xhr?.responseJSON?.message || 'No se pudo restaurar la persona';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            }
        });
    });
});

    // (Eliminado) Form oculto: ahora usamos AJAX con SweetAlert en el click handler
</script>
@endpush

<!-- Formulario global oculto para DELETE de persona -->
<form id="persona-destroy-form" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>