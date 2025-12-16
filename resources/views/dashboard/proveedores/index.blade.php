@extends('layouts.dashboard')

@section('title', 'Proveedores - ' . config('app.name'))

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0"><i class="fas fa-truck me-2 text-success"></i>Módulo de Proveedores</h2>
            <p class="text-muted mb-0">Gestión de proveedores vinculados a personas</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearProveedorModal">
            <i class="fas fa-plus me-2"></i>Nuevo Proveedor
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tablaProveedores">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Código</th>
                        <th>Proveedor</th>
                        <th>Categoría</th>
                        <th>Contacto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $proveedor)
                        @php
                            $persona = $proveedor->persona;
                            $estaDeshabilitado = $proveedor->trashed() || $proveedor->estado !== 'activo';
                        @endphp
                        <tr data-proveedor-id="{{ $proveedor->id }}">
                            <td>{{ $loop->iteration + ($proveedores->currentPage() - 1) * $proveedores->perPage() }}</td>
                            <td>{{ $proveedor->codigo_proveedor ?? '—' }}</td>
                            <td>
                                <strong>{{ $persona->nombre_completo ?? 'Sin persona' }}</strong><br>
                                @if($persona)
                                    <small class="text-muted">{{ $persona->tipo_documento }}-{{ $persona->documento }}</small>
                                @endif
                            </td>
                            <td>
                                @php $categorias = $proveedor->tiposProveedores->pluck('nombre_tipo')->all(); @endphp
                                @if(!empty($categorias))
                                    @foreach($categorias as $cat)
                                        <span class="badge bg-info text-dark me-1">{{ $cat }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-light text-dark">Sin categoría</span>
                                @endif
                            </td>
                            <td>
                                @if($proveedor->contacto_comercial)
                                    <div>{{ $proveedor->contacto_comercial }}</div>
                                @endif
                                @if($proveedor->telefono_comercial)
                                    <div>{{ $proveedor->telefono_comercial }}</div>
                                @endif
                                @if($proveedor->email_comercial)
                                    <small class="text-muted">{{ $proveedor->email_comercial }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $estaDeshabilitado ? 'bg-secondary' : 'bg-success' }} estado-badge">
                                    {{ $estaDeshabilitado ? 'Deshabilitado' : ucfirst($proveedor->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm acciones-proveedor" data-restore-url="{{ route('dashboard.proveedores.restore', $proveedor->id) }}">
                                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verProveedorModal" data-proveedor='@json($proveedor)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarProveedorModal" data-proveedor='@json($proveedor)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($estaDeshabilitado)
                                        <button type="button" class="btn btn-success btn-restaurar-proveedor" data-id="{{ $proveedor->id }}" data-url="{{ route('dashboard.proveedores.restore', $proveedor->id) }}">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('dashboard.proveedores.destroy', $proveedor) }}" method="POST" class="d-inline form-deshabilitar-proveedor">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-truck-loading fa-2x mb-3"></i>
                                <p>No se encontraron proveedores registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($proveedores->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $proveedores->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@include('dashboard.proveedores.modals.crear')
@include('dashboard.proveedores.modals.ver')
@include('dashboard.proveedores.modals.editar')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Selector personalizado de categorías (chips + disponibles)
    function initCategoriaSelector(container) {
        const $container = $(container);
        const $seleccionadas = $container.find('.categorias-seleccionadas');
        const $disponibles = $container.find('.categorias-disponibles');
        const targetInput = $container.data('target-input');
        const targetSummary = $container.data('target-summary');

        function actualizarHiddenYResumen() {
            const ids = [];
            const nombres = [];
            $seleccionadas.find('.chip').each(function() {
                ids.push($(this).data('id'));
                nombres.push($(this).data('nombre'));
            });
            // Guardar IDs separados por coma en hidden
            $(targetInput).val(ids.join(','));
            // Construir resumen con formato (x Nombre)
            const conteo = nombres.reduce((acc, nombre) => {
                acc[nombre] = (acc[nombre] || 0) + 1;
                return acc;
            }, {});
            const resumen = Object.entries(conteo).map(([nombre, count]) => `(${count} ${nombre})`).join(' ');
            $(targetSummary).val(resumen);
        }

        function agregarChip(id, nombre) {
            // Evitar duplicados
            if ($seleccionadas.find(`.chip[data-id="${id}"]`).length) return;
            const $chip = $(`<span class="badge bg-primary chip" data-id="${id}" data-nombre="${nombre}" style="cursor:pointer">${nombre} ×</span>`);
            $chip.on('click', function() {
                // Al quitar, vuelve a la lista disponible
                $chip.remove();
                const $btn = $(`<button type="button" class="btn btn-sm btn-outline-primary categoria-item" data-id="${id}" data-nombre="${nombre}">${nombre}</button>`);
                $btn.on('click', function() {
                    agregarChip(id, nombre);
                    $(this).remove();
                });
                $disponibles.append($btn);
                actualizarHiddenYResumen();
            });
            $seleccionadas.append($chip);
            actualizarHiddenYResumen();
        }

        // Click en disponibles: mover a seleccionadas
        $disponibles.find('.categoria-item').each(function() {
            $(this).on('click', function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                agregarChip(id, nombre);
                $(this).remove();
            });
        });
    }
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const tabla = $('#tablaProveedores').DataTable({
        responsive: true,
        language: { url: "{{ asset('datatables-i18n-es.json') }}" },
        order: [[5, 'asc'], [1, 'asc']],
        columnDefs: [
            { orderable: false, targets: [0,6] }
        ]
    });

    tabla.on('order.dt search.dt draw.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
            this.data(i++);
        });
    });

    // Inicializar selectores al abrir modales
    initCategoriaSelector('#crearSelectorCategorias');

    $('#verProveedorModal').on('show.bs.modal', function(event) {
        const proveedorData = $(event.relatedTarget).data('proveedor');
        const proveedor = typeof proveedorData === 'string' ? JSON.parse(proveedorData) : proveedorData;
        const modal = $(this);
        const persona = proveedor.persona || {};
        const tipos = proveedor.tipos_proveedores || proveedor.tiposProveedores || [];

        modal.find('#verCodigo').text(proveedor.codigo_proveedor || 'No asignado');
        modal.find('#verNombre').text(persona.nombre_completo || 'Sin persona asociada');
        modal.find('#verDocumento').text((persona.tipo_documento || '-') + '-' + (persona.documento || '-'));
        modal.find('#verCategoria').text(tipos.map(t => t.nombre_tipo).join(', ') || proveedor.categoria || '—');
        modal.find('#verProductos').text(proveedor.productos_servicios || '—');
        modal.find('#verEspecializacion').text(proveedor.especializacion || '—');
        modal.find('#verContacto').text(proveedor.contacto_comercial || '—');
        modal.find('#verTelefono').text(proveedor.telefono_comercial || '—');
        modal.find('#verEmail').text(proveedor.email_comercial || '—');
        modal.find('#verCalificacion').text(proveedor.calificacion ?? '—');
        modal.find('#verEstado').text(proveedor.estado || '—');
        modal.find('#verBanco').text(proveedor.banco || '—');
        modal.find('#verTipoCuenta').text(proveedor.tipo_cuenta || '—');
        modal.find('#verNumeroCuenta').text(proveedor.numero_cuenta || '—');
    });

    $('#editarProveedorModal').on('show.bs.modal', function(event) {
        const proveedorData = $(event.relatedTarget).data('proveedor');
        const proveedor = typeof proveedorData === 'string' ? JSON.parse(proveedorData) : proveedorData;
        const tipos = proveedor.tipos_proveedores || proveedor.tiposProveedores || [];
        const modal = $(this);
        modal.find('form').attr('action', '/dashboard/proveedores/' + proveedor.id);
        modal.find('#editPersona').val(proveedor.persona_id);
        modal.find('#editCodigoProveedor').val(proveedor.codigo_proveedor);
        modal.find('#editProductos').val(proveedor.productos_servicios);
        modal.find('#editEspecializacion').val(proveedor.especializacion);
        modal.find('#editContacto').val(proveedor.contacto_comercial);
        modal.find('#editTelefono').val(proveedor.telefono_comercial);
        modal.find('#editEmail').val(proveedor.email_comercial);
        // Inicializar selector personalizado y preseleccionar
        initCategoriaSelector('#editarSelectorCategorias');
        const $editarContainer = $('#editarSelectorCategorias');
        const $disponibles = $editarContainer.find('.categorias-disponibles');
        const $seleccionadas = $editarContainer.find('.categorias-seleccionadas');
        const preseleccion = Array.isArray(tipos) ? tipos.map(t => ({ id: t.id, nombre: t.nombre_tipo })) : [];
        preseleccion.forEach(({id, nombre}) => {
            // Si existe el botón disponible con ese id, simular click para moverlo a chips
            const $btn = $disponibles.find(`.categoria-item[data-id="${id}"]`);
            if ($btn.length) {
                $btn.trigger('click');
            } else {
                // Si no está en disponibles (ya chip), asegurar conteo
                if (!$seleccionadas.find(`.chip[data-id="${id}"]`).length) {
                    const $chip = $(`<span class="badge bg-primary chip" data-id="${id}" data-nombre="${nombre}" style="cursor:pointer">${nombre} ×</span>`);
                    $seleccionadas.append($chip);
                }
            }
        });
        // Actualizar hidden y resumen
        const ids = preseleccion.map(p => p.id).join(',');
        $('#editarTiposSeleccionados').val(ids);
        const resumen = preseleccion.map(p => `(1 ${p.nombre})`).join(' ');
        $('#editarResumenCategorias').val(resumen);
        modal.find('#editCalificacion').val(proveedor.calificacion);
        modal.find('#editObservaciones').val(proveedor.observaciones_calificacion);
        modal.find('#editEstado').val(proveedor.estado);
        modal.find('#editFechaRegistro').val(proveedor.fecha_registro);
        modal.find('#editFechaUltimaCompra').val(proveedor.fecha_ultima_compra);
        modal.find('#editBanco').val(proveedor.banco);
        modal.find('#editTipoCuenta').val(proveedor.tipo_cuenta);
        modal.find('#editNumeroCuenta').val(proveedor.numero_cuenta);
    });

    $('#formCrearProveedor').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            beforeSend: () => form.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...'),
            success: (response) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar');
                Swal.fire({ icon: 'success', title: 'Éxito', text: response.message, timer: 1500, showConfirmButton: false });
                setTimeout(() => window.location.reload(), 1200);
            },
            error: (xhr) => handleAjaxError(xhr, form.find('button[type="submit"]'), '<i class="fas fa-save me-2"></i>Guardar')
        });
    });

    $('#formEditarProveedor').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            beforeSend: () => form.find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Actualizando...'),
            success: (response) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Actualizar');
                Swal.fire({ icon: 'success', title: 'Actualizado', text: response.message, timer: 1500, showConfirmButton: false });
                setTimeout(() => window.location.reload(), 1200);
            },
            error: (xhr) => handleAjaxError(xhr, form.find('button[type="submit"]'), '<i class="fas fa-save me-2"></i>Actualizar')
        });
    });

    $(document).on('submit', '.form-deshabilitar-proveedor', function(e) {
        e.preventDefault();
        const form = $(this);
        Swal.fire({
            title: 'Deshabilitar proveedor',
            text: 'Podrás restaurarlo luego.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, deshabilitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: () => {
                        Swal.fire({ icon: 'success', title: 'Deshabilitado', timer: 1200, showConfirmButton: false });
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: (xhr) => handleAjaxError(xhr)
                });
            }
        });
    });

    $(document).on('click', '.btn-restaurar-proveedor', function() {
        const boton = $(this);
        const url = boton.data('url');
        Swal.fire({
            title: 'Restaurar proveedor',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Restaurar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {},
                    success: () => {
                        Swal.fire({ icon: 'success', title: 'Restaurado', timer: 1200, showConfirmButton: false });
                        setTimeout(() => window.location.reload(), 1000);
                    },
                    error: (xhr) => handleAjaxError(xhr)
                });
            }
        });
    });

    function handleAjaxError(xhr, button, defaultLabel) {
        if (button && defaultLabel) {
            button.prop('disabled', false).html(defaultLabel);
        }
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            let errorHtml = '<ul>';
            $.each(errors, function(key, value) {
                errorHtml += '<li>' + value[0] + '</li>';
            });
            errorHtml += '</ul>';
            Swal.fire({ icon: 'error', title: 'Error de validación', html: errorHtml });
        } else {
            const message = xhr?.responseJSON?.message || 'Error al procesar la solicitud';
            Swal.fire({ icon: 'error', title: 'Error', text: message });
        }
    }
});
</script>
@endpush
