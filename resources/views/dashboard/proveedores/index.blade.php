@extends('layouts.material')

@section('title', config('app.name'))

@push('styles')
<style>
    .acciones-proveedor .btn { display: inline-flex; align-items: center; justify-content: center; }
    .acciones-proveedor .btn .material-icons { font-size: 18px; line-height: 1; }
    .acciones-proveedor .btn + .btn { margin-left: -1px; }
    .acciones-proveedor form { display: inline-block; margin: 0; }
    .acciones-proveedor .btn { border-radius: 0; }
    .acciones-proveedor .btn:first-child { border-top-left-radius: .2rem; border-bottom-left-radius: .2rem; }
    .acciones-proveedor .btn:last-child { border-top-right-radius: .2rem; border-bottom-right-radius: .2rem; }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-0">Módulo de Proveedores</h2>
                <p class="text-muted mb-0">Gestión de proveedores vinculados a personas</p>
        </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearProveedorModal">
                <i class="material-icons me-2">add</i>Nuevo Proveedor
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
                                        <span class="badge bg-info me-1">{{ $cat }}</span>
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
                                <span class="badge {{ $estaDeshabilitado ? 'text-bg-secondary' : 'text-bg-success' }} estado-badge">
                                    <span class="badge {{ $estaDeshabilitado ? 'bg-secondary' : 'bg-success' }} estado-badge">
                                    {{ $estaDeshabilitado ? 'Deshabilitado' : ucfirst($proveedor->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm acciones-proveedor" data-restore-url="{{ route('dashboard.proveedores.restore', $proveedor->id) }}">
                                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verProveedorModal" data-proveedor='@json($proveedor, JSON_HEX_APOS | JSON_HEX_QUOT)'>
                                            <i class="material-icons">visibility</i>
                                    </button>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarProveedorModal" data-proveedor='@json($proveedor, JSON_HEX_APOS | JSON_HEX_QUOT)'>
                                            <i class="material-icons">edit</i>
                                    </button>
                                    @if($estaDeshabilitado)
                                        <button type="button" class="btn btn-success btn-restaurar-proveedor" data-id="{{ $proveedor->id }}" data-url="{{ route('dashboard.proveedores.restore', $proveedor->id) }}">
                                                <i class="material-icons">restore</i>
                                        </button>
                                    @else
                                        <form action="{{ route('dashboard.proveedores.destroy', $proveedor) }}" method="POST" class="d-inline form-deshabilitar-proveedor">
                                            @csrf
                                            @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="material-icons">block</i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                    <i class="material-icons" style="font-size:2rem;">local_shipping</i>
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

        if (!$container.data('original-categorias')) {
            $container.data('original-categorias', $disponibles.html());
        } else {
            $disponibles.html($container.data('original-categorias'));
        }

        function actualizarHiddenYResumen() {
            const nombres = [];
            const $target = $(targetInput);
            $target.empty();
            $seleccionadas.find('.chip').each(function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                nombres.push(nombre);
                $target.append(`<input type="hidden" name="tipos_proveedores[]" value="${id}">`);
            });
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
            const $chip = $(`<span class="badge bg-success chip" data-id="${id}" data-nombre="${nombre}" style="cursor:pointer">${nombre} ×</span>`);
            $chip.on('click', function() {
                // Al quitar, vuelve a la lista disponible
                $chip.remove();
                const $btn = $(`<button type="button" class="btn btn-sm btn-outline-success categoria-item" data-id="${id}" data-nombre="${nombre}">${nombre}</button>`);
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

    function renderCategoriasBadges(tipos) {
        const normalizados = (tipos || []).map(t => ({
            id: t.id ?? t.tipo_proveedor_id ?? t.pivot?.tipo_proveedor_id ?? t,
            nombre_tipo: t.nombre_tipo ?? t.nombre ?? t.name ?? t
        })).filter(t => t.id && t.nombre_tipo);

        if (!normalizados.length) {
            return '<span class="badge text-bg-light">Sin categoría</span>';
        }
        return normalizados.map(t => `<span class=\"badge bg-info me-1\">${t.nombre_tipo}</span>`).join('');
    }

    function renderContacto(proveedor) {
        const partes = [];
        if (proveedor.contacto_comercial) partes.push(`<div>${proveedor.contacto_comercial}</div>`);
        if (proveedor.telefono_comercial) partes.push(`<div>${proveedor.telefono_comercial}</div>`);
        if (proveedor.email_comercial) partes.push(`<small class="text-muted">${proveedor.email_comercial}</small>`);
        return partes.join('') || '';
    }

    function renderEstado(proveedor) {
        const estaDeshabilitado = proveedor.deleted_at !== null || proveedor.estado !== 'activo';
        const clase = estaDeshabilitado ? 'text-bg-secondary' : 'text-bg-success';
        const texto = estaDeshabilitado ? 'Deshabilitado' : (proveedor.estado ? proveedor.estado.charAt(0).toUpperCase() + proveedor.estado.slice(1) : '');
        return `<span class="badge ${clase} estado-badge">${texto}</span>`;
    }

    function renderProveedorCell(proveedor) {
        const persona = proveedor.persona || {};
        const nombre = persona.nombre_completo
            || [persona.nombres, persona.apellidos].filter(Boolean).join(' ')
            || persona.razon_social
            || 'Sin persona';
        const doc = persona.tipo_documento && persona.documento ? `${persona.tipo_documento}-${persona.documento}` : '';
        return `<strong>${nombre}</strong><br>${doc ? `<small class="text-muted">${doc}</small>` : ''}`;
    }

    function buildAcciones(proveedor) {
        const estaDeshabilitado = proveedor.deleted_at !== null || proveedor.estado !== 'activo';
        const dataProveedor = $('<div>').text(JSON.stringify(proveedor)).html();
        const restoreUrl = `/dashboard/proveedores/${proveedor.id}/restore`;
        const destroyUrl = `/dashboard/proveedores/${proveedor.id}`;
        const botones = [
            `<button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#verProveedorModal" data-proveedor='${dataProveedor}'>
                <i class="fas fa-eye"></i>
            </button>`,
            `<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarProveedorModal" data-proveedor='${dataProveedor}'>
                <i class="fas fa-edit"></i>
            </button>`
        ];
        if (estaDeshabilitado) {
            botones.push(`<button type="button" class="btn btn-success btn-restaurar-proveedor" data-id="${proveedor.id}" data-url="${restoreUrl}"><i class="fas fa-undo"></i></button>`);
        } else {
            botones.push(`<form action="${destroyUrl}" method="POST" class="d-inline form-deshabilitar-proveedor">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-ban"></i></button>
            </form>`);
        }
        return `<div class="btn-group btn-group-sm acciones-proveedor" data-restore-url="${restoreUrl}">${botones.join('')}</div>`;
    }

    function addRow(proveedor) {
        const persona = proveedor.persona || {};
        const rowNode = tabla.row.add([
            '',
            proveedor.codigo_proveedor || '—',
            renderProveedorCell(proveedor),
            renderCategoriasBadges(proveedor.tipos_proveedores || proveedor.tiposProveedores),
            renderContacto(proveedor),
            renderEstado(proveedor),
            buildAcciones(proveedor)
        ]).draw(false).node();
        $(rowNode).attr('data-proveedor-id', proveedor.id);
    }

    function updateRow(proveedor) {
        const fila = $(`#tablaProveedores tbody tr[data-proveedor-id="${proveedor.id}"]`);
        const row = tabla.row(fila);
        row.data([
            fila.find('td').eq(0).text(),
            proveedor.codigo_proveedor || '—',
            renderProveedorCell(proveedor),
            renderCategoriasBadges(proveedor.tipos_proveedores || proveedor.tiposProveedores),
            renderContacto(proveedor),
            renderEstado(proveedor),
            buildAcciones(proveedor)
        ]).draw(false);
        fila.attr('data-proveedor-id', proveedor.id);
    }

    tabla.on('order.dt search.dt draw.dt', function() {
        let i = 1;
        tabla.cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
            this.data(i++);
        });
    });

    // Inicializar selectores al abrir modales
    initCategoriaSelector('#crearSelectorCategorias');

    // Evitar problemas de stacking/transform: mover modales al body
    ['#crearProveedorModal', '#verProveedorModal', '#editarProveedorModal'].forEach(id => {
        const $modal = $(id);
        if ($modal.length && $modal.parent()[0] !== document.body) {
            console.log('Reubicando modal en body:', id);
            $modal.appendTo('body');
        }
    });

    function getProveedorFromEvent(event) {
        const raw = $(event.relatedTarget).data('proveedor');
        if (!raw) return {};
        if (typeof raw === 'object') return raw;
        try {
            return JSON.parse(raw);
        } catch (err) {
            console.error('No se pudo parsear proveedor', err, raw);
            return {};
        }
    }

    function formatDateYMD(value) {
        if (!value) return '';
        const date = new Date(value);
        return Number.isNaN(date.getTime()) ? '' : date.toISOString().slice(0, 10);
    }

    $('#verProveedorModal').on('show.bs.modal', function(event) {
        const proveedor = getProveedorFromEvent(event);
        const modal = $(this);
        const persona = proveedor.persona || {};
        const tipos = proveedor.tipos_proveedores || proveedor.tiposProveedores || [];
        const productosRaw = proveedor.productos_servicios;
        const productosList = Array.isArray(productosRaw)
            ? productosRaw
            : (typeof productosRaw === 'string' && productosRaw.trim()
                ? productosRaw.split(',').map(p => p.trim()).filter(Boolean)
                : []);

        modal.find('#verCodigo').text(proveedor.codigo_proveedor || 'No asignado');
        modal.find('#verNombre').text(persona.nombre_completo || 'Sin persona asociada');
        modal.find('#verPersonaNombre').text(persona.nombre_completo || 'Sin persona asociada');
        modal.find('#verDocumento').text((persona.tipo_documento || '-') + '-' + (persona.documento || '-'));
        modal.find('#verCategoria').text(tipos.map(t => t.nombre_tipo).join(', ') || proveedor.categoria || '—');
        modal.find('#verProductos').html(productosList.length
            ? productosList.map(p => `<span class="badge bg-light text-dark border me-1">${p}</span>`).join(' ')
            : '—');
        modal.find('#verEspecializacion').text(proveedor.especializacion || '—');
        modal.find('#verTelefono').text(persona.telefono || '—');
        modal.find('#verEmail').text(persona.email || '—');
        const calificacionNum = Number(proveedor.calificacion ?? 0);
        const calif = Number.isFinite(calificacionNum) ? Math.max(0, Math.min(5, calificacionNum)) : 0;
        const filled = Math.round(calif);
        const starsHtml = Array.from({ length: 5 }, (_, i) => i < filled
            ? '<i class="material-icons" style="font-size:18px;">star</i>'
            : '<i class="material-icons text-muted" style="font-size:18px;">star_border</i>'
        ).join('');
        modal.find('#verCalificacionStars').html(starsHtml);
        modal.find('#verCalificacionValor').text(filled ? `${filled}/5` : '—');
        modal.find('#verEstado').text(proveedor.estado || '—');
        modal.find('#verBanco').text(proveedor.banco || '—');
        modal.find('#verTipoCuenta').text(proveedor.tipo_cuenta || '—');
        modal.find('#verNumeroCuenta').text(proveedor.numero_cuenta || '—');
    });

    $('#editarProveedorModal').on('show.bs.modal', function(event) {
        try {
            console.log('Editar modal: show.bs.modal triggered');
            const proveedor = getProveedorFromEvent(event);
            console.log('Proveedor parsed:', proveedor);
            const tipos = proveedor.tipos_proveedores || proveedor.tiposProveedores || [];
            const modal = $(this);
            console.log('Tipos preseleccion:', tipos);
            modal.find('form').attr('action', '/dashboard/proveedores/' + proveedor.id);
            modal.find('#editPersona').val(proveedor.persona_id);
            // Inicializar selector personalizado y preseleccionar
            initCategoriaSelector('#editarSelectorCategorias');
            const $editarContainer = $('#editarSelectorCategorias');
            const $disponibles = $editarContainer.find('.categorias-disponibles');
            const $seleccionadas = $editarContainer.find('.categorias-seleccionadas');
        const preseleccion = Array.isArray(tipos) ? tipos.map(t => ({ id: t.id, nombre: t.nombre_tipo })) : [];
                const actualizarHiddenYResumen = () => {
                    const $cont = $editarContainer;
                    const $chips = $cont.find('.chip');
                    const $target = $($cont.data('target-input'));
                    const $summary = $($cont.data('target-summary'));
                    const nombres = [];
                    $target.empty();
                    $chips.each(function(){
                        const id = $(this).data('id');
                        const nombre = $(this).data('nombre');
                        nombres.push(nombre);
                        $target.append(`<input type="hidden" name="tipos_proveedores[]" value="${id}">`);
                    });
                    const conteo = nombres.reduce((acc, nombre) => {
                        acc[nombre] = (acc[nombre] || 0) + 1;
                        return acc;
                    }, {});
                    const resumen = Object.entries(conteo).map(([nombre, count]) => `(${count} ${nombre})`).join(' ');
                    $summary.val(resumen);
                };

                const ensureChip = (id, nombre) => {
                    if ($seleccionadas.find(`.chip[data-id="${id}"]`).length) return;
                    const $chip = $(`<span class="badge bg-success chip" data-id="${id}" data-nombre="${nombre}" style="cursor:pointer">${nombre} ×</span>`);
                    $chip.on('click', function() {
                        $chip.remove();
                        const $btnNuevo = $(`<button type="button" class="btn btn-sm btn-outline-success categoria-item" data-id="${id}" data-nombre="${nombre}">${nombre}</button>`);
                        $btnNuevo.on('click', function() {
                            ensureChip(id, nombre);
                            $(this).remove();
                        });
                        $disponibles.append($btnNuevo);
                        actualizarHiddenYResumen();
                    });
                    $seleccionadas.append($chip);
                    actualizarHiddenYResumen();
                };

                preseleccion.forEach(({id, nombre}) => {
                    const $btn = $disponibles.find(`.categoria-item[data-id="${id}"]`);
                    if ($btn.length) {
                        $btn.trigger('click');
                    } else {
                        ensureChip(id, nombre);
                    }
                });
                actualizarHiddenYResumen();
        const productosRaw = proveedor.productos_servicios;
        const productosList = Array.isArray(productosRaw)
            ? productosRaw
            : (typeof productosRaw === 'string' && productosRaw.trim()
                ? productosRaw.split(',').map(p => p.trim()).filter(Boolean)
                : []);
            console.log('Productos list edit:', productosList);
            if (window.setEditProductos) {
                window.setEditProductos(productosList);
            }
            console.log('Modal edit populated');
        } catch (error) {
            console.error('Error al abrir modal de edición', error);
            Swal.fire({ icon: 'error', title: 'No se pudo cargar el proveedor', text: 'Revisa la consola para más detalles.' });
            event.preventDefault();
            event.stopImmediatePropagation();
        }
    });

    $('#editarProveedorModal').on('shown.bs.modal', function() {
        console.log('Editar modal: shown.bs.modal (visible). Clases:', $(this).attr('class'), 'display:', $(this).css('display'));
    });

    $('#editarProveedorModal').on('hidden.bs.modal', function() {
        console.log('Editar modal: hidden.bs.modal');
    });

    $('#formCrearProveedor').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        if (!form.find('select[name="persona_id"]').val()) {
            Swal.fire({ icon: 'warning', title: 'Persona requerida', text: 'Seleccione una persona.' });
            return;
        }
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            beforeSend: () => form.find('button[type="submit"]').prop('disabled', true).html('<i class="material-icons me-2">hourglass_empty</i>Guardando...'),
            success: (response) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="material-icons me-2">save</i>Guardar');
                const proveedor = response.data;
                addRow(proveedor);
                $('#crearProveedorModal').modal('hide');
                form.trigger('reset');
                form.find('.categorias-seleccionadas .chip').remove();
                form.find('.categorias-disponibles .categoria-item').off('click');
                initCategoriaSelector('#crearSelectorCategorias');
                Swal.fire({ icon: 'success', title: 'Éxito', text: response.message, timer: 1200, showConfirmButton: false });
            },
            error: (xhr) => handleAjaxError(xhr, form.find('button[type="submit"]'), '<i class="fas fa-save me-2"></i>Guardar')
        });
    });

    $('#formEditarProveedor').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        if (!form.find('select[name="persona_id"]').val()) {
            Swal.fire({ icon: 'warning', title: 'Persona requerida', text: 'Seleccione una persona.' });
            return;
        }
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            beforeSend: () => form.find('button[type="submit"]').prop('disabled', true).html('<i class="material-icons me-2">hourglass_empty</i>Actualizando...'),
            success: (response) => {
                form.find('button[type="submit"]').prop('disabled', false).html('<i class="material-icons me-2">save</i>Actualizar');
                const proveedor = response.data;
                updateRow(proveedor);
                $('#editarProveedorModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Actualizado', text: response.message, timer: 1200, showConfirmButton: false });
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
