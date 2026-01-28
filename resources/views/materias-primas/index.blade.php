<!-- resources/views/materias-primas/index.blade.php -->
@extends('layouts.material')

@section('title', 'Materias Primas - Inventario')

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        font-weight: 600;
    }
    
    .estado-inventario.critico { color: #dc3545; font-weight: bold; }
    .estado-inventario.agotado { color: #6c757d; font-weight: bold; }
    .estado-inventario.normal { color: #198754; }
    
    /* Estilos para botones de acción */
    .acciones-materia .btn { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center;
        padding: 0.25rem 0.5rem;
    }
    .acciones-materia .btn .material-icons { 
        font-size: 18px; 
        line-height: 1; 
    }
    .acciones-materia .btn + .btn { 
        margin-left: -1px; 
    }
    .acciones-materia .btn:first-child { 
        border-top-left-radius: .2rem; 
        border-bottom-left-radius: .2rem; 
    }
    .acciones-materia .btn:last-child { 
        border-top-right-radius: .2rem; 
        border-bottom-right-radius: .2rem; 
    }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0">
                    <i class="material-icons me-2 text-warning">inventory</i>Inventario de Materias Primas
                </h2>
                <p class="text-muted mb-0">Gestión de materias primas y control de inventario</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#entradaInventarioModal">
                    <i class="material-icons me-2">input</i>Registrar Entrada
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMateriaModal">
                    <i class="material-icons me-2">add_circle</i>Nueva Materia Prima
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de estado del inventario -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Estado del Inventario</h5>
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="material-icons me-1">filter_list</i>Filtros
            </button>
        </div>
        
        <ul class="nav nav-tabs" id="estadoTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="todos-tab" data-bs-toggle="tab" href="#todos" role="tab">
                    TODOS ({{ $totalMaterias ?? 0 }})
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="criticos-tab" data-bs-toggle="tab" href="#criticos" role="tab">
                    CRÍTICOS ({{ $criticos ?? 0 }})
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="agotados-tab" data-bs-toggle="tab" href="#agotados" role="tab">
                    AGOTADOS ({{ $agotados ?? 0 }})
                </a>
            </li>
        </ul>
        
        <div class="tab-content pt-3" id="estadoTabContent">
        <!-- Tab Todos -->
<div class="tab-pane fade show active" id="todos" role="tabpanel">
    @include('materias-primas.partials.tabla-materias', [
        'materiasPrimas' => $materiasPrimas, 
        'filtroEstado' => null
    ])
</div>
            
           <!-- Tab Críticos -->
<div class="tab-pane fade" id="criticos" role="tabpanel">
    @php
        $materiasCriticas = $materiasPrimasCollection->filter(function($materia) {
            return $materia->inventario && $materia->inventario->estado == 'critico';
        });
    @endphp
    
    @if($materiasCriticas->count() > 0)
        @include('materias-primas.partials.tabla-materias', [
            'materiasPrimas' => $materiasCriticas,
            'filtroEstado' => 'critico'
        ])
    @else
        <div class="text-center text-muted py-5">
            <i class="material-icons" style="font-size:3rem;">check_circle</i>
            <h5 class="mt-3">No hay materias primas en estado crítico</h5>
        </div>
    @endif
</div>
           <!-- Tab Agotados -->
<div class="tab-pane fade" id="agotados" role="tabpanel">
    @php
        $materiasAgotadas = $materiasPrimasCollection->filter(function($materia) {
            return $materia->inventario && $materia->inventario->estado == 'agotado';
        });
    @endphp
    
    @if($materiasAgotadas->count() > 0)
        @include('materias-primas.partials.tabla-materias', [
            'materiasPrimas' => $materiasAgotadas, 
            'filtroEstado' => 'agotado'
        ])
    @else
        <div class="text-center text-muted py-5">
            <i class="material-icons" style="font-size:3rem;">inventory_2</i>
            <h5 class="mt-3">No hay materias primas agotadas</h5>
        </div>
    @endif
</div>
        </div>
    </div>
</div>

<!-- Modales -->
@include('materias-primas.partials.modals')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar DataTables
    const tabla = $('#materiasTable').DataTable({
        responsive: true,
        language: {
            url: "{{ asset('datatables-i18n-es.json') }}",
            emptyTable: "No hay materias primas registradas",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            lengthMenu: "Mostrar _MENU_ registros",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron registros coincidentes",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [8] }, // Columna acciones no ordenable
            { searchable: false, targets: [8] }, // Columna acciones no buscable
            { className: "text-center", targets: [3,4,5,6] } // Centrar columnas numéricas
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        pageLength: 10
    });

    // Cambiar entre pestañas
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        tabla.columns.adjust().responsive.recalc();
    });

    // Ver materia prima
    $(document).on('click', '.btn-ver-materia', function() {
        const materiaId = $(this).data('id');
        
        $.ajax({
            url: '/materias-primas/' + materiaId,
            method: 'GET',
            success: function(response) {
                $('#showMateriaModal .modal-body').html(response);
                $('#showMateriaModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la información de la materia prima'
                });
            }
        });
    });

    // Editar materia prima
    $(document).on('click', '.btn-editar-materia', function() {
        const materiaId = $(this).data('id');
        
        $.ajax({
            url: '/materias-primas/' + materiaId + '/edit',
            method: 'GET',
            success: function(response) {
                $('#editMateriaModal .modal-body').html(response);
                $('#editMateriaModal').modal('show');
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo cargar la información para editar'
                });
            }
        });
    });

    // Deshabilitar materia prima
    $(document).on('click', '.btn-deshabilitar-materia', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        const fila = $(this).closest('tr');
        
        Swal.fire({
            title: 'Deshabilitar materia prima',
            text: '¿Está seguro? La materia prima se marcará como inactiva.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, deshabilitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: { _method: 'DELETE' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deshabilitada',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo deshabilitar la materia prima'
                        });
                    }
                });
            }
        });
    });

    // Habilitar materia prima
    $(document).on('click', '.btn-habilitar-materia', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        const fila = $(this).closest('tr');
        
        Swal.fire({
            title: 'Habilitar materia prima',
            text: '¿Está seguro de habilitar esta materia prima?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, habilitar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Habilitada',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo habilitar la materia prima'
                        });
                    }
                });
            }
        });
    });

    // Calcular costo total en modal de entrada
    $('#entradaInventarioModal #cantidad, #entradaInventarioModal #costo_unitario').on('keyup', function() {
        const cantidad = parseFloat($('#entradaInventarioModal #cantidad').val()) || 0;
        const costoUnitario = parseFloat($('#entradaInventarioModal #costo_unitario').val()) || 0;
        const costoTotal = cantidad * costoUnitario;
        $('#entradaInventarioModal #costo_total').val(costoTotal.toFixed(2));
    });

   // Calcular punto de reorden en modal de creación - MODIFICADO
$('#createMateriaModal input[name="inventario[stock_minimo]"], #createMateriaModal input[name="inventario[stock_maximo]"]').on('keyup', function() {
    const stockMin = parseFloat($('#createMateriaModal input[name="inventario[stock_minimo]"]').val()) || 0;
    const stockMax = parseFloat($('#createMateriaModal input[name="inventario[stock_maximo]"]').val()) || 0;
    const puntoReorden = stockMin + ((stockMax - stockMin) * 0.3);
    $('#createMateriaModal input[name="inventario[punto_reorden]"]').val(puntoReorden.toFixed(3));
});
});
</script>
@endpush