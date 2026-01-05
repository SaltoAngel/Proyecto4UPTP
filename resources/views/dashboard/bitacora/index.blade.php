@extends('layouts.material')

@section('title', 'Bitácora')

@push('styles')
<style>
    /* Mejora alineación y legibilidad de la tabla */
    #tablaBitacora th, #tablaBitacora td { vertical-align: middle; }
    #tablaBitacora td:nth-child(1) { width: 110px; }
    #tablaBitacora td:nth-child(2) { width: 220px; }
    #tablaBitacora td:nth-child(6) { white-space: nowrap; }
    #tablaBitacora td:nth-child(7) { width: 90px; text-align: center; }
    #tablaBitacora .badge { letter-spacing: 0.3px; }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1 d-flex align-items-center gap-2">
                <span class="material-icons text-primary">history</span>
                Bitácora de Cambios
            </h2>
            <p class="text-muted mb-0">Auditoría de acciones por módulo, usuario y fechas</p>
        </div>
        <button class="btn btn-outline-primary d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosLive" aria-expanded="false">
            <i class="material-icons me-1" style="font-size:1.1rem;">tune</i>Filtros
        </button>
    </div>
</div>

<div class="collapse mb-3" id="filtrosLive">
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Módulo</label>
                    <select id="filtroModulo" class="form-select">
                        <option value="">Todos</option>
                        @foreach($modulos ?? [] as $mod)
                            <option value="{{ $mod }}">{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Acción</label>
                    <select id="filtroAccion" class="form-select">
                        <option value="">Todas</option>
                        @foreach($acciones ?? [] as $acc)
                            <option value="{{ $acc }}">{{ $acc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" id="filtroUsuario" class="form-control" placeholder="Buscar usuario">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" id="limpiarFiltros" class="btn btn-outline-secondary w-100"><i class="material-icons me-1" style="font-size:1.1rem;">restart_alt</i>Limpiar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablaBitacora">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Usuario</th>
                        <th>Módulo</th>
                        <th>Acción</th>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bitacora as $registro)
                    @php
                        $accionKey = Str::slug($registro->accion ?? '', '_');
                        $accionMap = [
                            'create' => ['class' => 'bg-success text-white', 'label' => 'Creación'],
                            'creacion' => ['class' => 'bg-success text-white', 'label' => 'Creación'],
                            'update' => ['class' => 'bg-warning text-white', 'label' => 'Actualización'],
                            'delete' => ['class' => 'bg-danger text-white', 'label' => 'Eliminación'],
                            'login' => ['class' => 'bg-info text-white', 'label' => 'Ingreso'],
                            'logout' => ['class' => 'bg-secondary text-white', 'label' => 'Salida'],
                            'bloquear' => ['class' => 'bg-dark text-white', 'label' => 'Bloqueo'],
                            'deshabilitar' => ['class' => 'bg-secondary text-white', 'label' => 'Deshabilitado'],
                            'restaurar' => ['class' => 'bg-success text-white', 'label' => 'Restaurado'],
                            'actualizar_estado' => ['class' => 'bg-primary text-white', 'label' => 'Cambio de estado'],
                        ];
                        $accionConfig = $accionMap[$accionKey] ?? ['class' => 'bg-secondary text-white', 'label' => ucfirst(strtolower($registro->accion ?? 'Acción'))];

                        $moduloKey = strtolower($registro->modulo ?? '');
                        $moduloMap = [
                            'personas' => ['class' => 'bg-info text-white', 'label' => 'Personas'],
                            'person' => ['class' => 'bg-info text-white', 'label' => 'Personas'],
                            'proveedores' => ['class' => 'bg-primary text-white', 'label' => 'Proveedores'],
                            'usuarios' => ['class' => 'bg-dark text-white', 'label' => 'Usuarios'],
                            'reportes' => ['class' => 'bg-success text-white', 'label' => 'Reportes'],
                            'autenticación' => ['class' => 'bg-warning text-white', 'label' => 'Autenticación'],
                            'autenticacion' => ['class' => 'bg-warning text-white', 'label' => 'Autenticación'],
                        ];
                        $moduloConfig = $moduloMap[$moduloKey] ?? ['class' => 'bg-secondary text-white', 'label' => ucfirst($registro->modulo ?? 'N/D')];

                        $nombreUsuario = 'Usuario eliminado';
                        if ($registro->user) {
                            $nombreUsuario = optional($registro->user->persona)->nombre_completo
                                ?? $registro->user->name
                                ?? ($registro->user->email ?? 'Usuario');
                        } elseif (!empty($registro->datos_nuevos)) {
                            $nombreUsuario = $registro->datos_nuevos['nombre_completo']
                                ?? trim(($registro->datos_nuevos['nombres'] ?? '') . ' ' . ($registro->datos_nuevos['apellidos'] ?? ''))
                                ?? $registro->datos_nuevos['name']
                                ?? $registro->datos_nuevos['email']
                                ?? $registro->datos_nuevos['user_name']
                                ?? 'Usuario';
                        }
                    @endphp
                    @php
                        $payload = [
                            'codigo' => $registro->codigo,
                            'modulo' => $registro->modulo,
                            'accion' => $registro->accion,
                            'detalle' => $registro->detalle,
                            'fecha' => $registro->created_at?->toIso8601String(),
                            'usuario' => optional($registro->user->persona)->nombre_completo
                                        ?? $registro->user->name
                                        ?? $registro->user->email
                                        ?? 'Usuario eliminado',
                            'datos_anteriores' => $registro->datos_anteriores,
                            'datos_nuevos' => $registro->datos_nuevos,
                        ];
                    @endphp
                    <tr>
                        <td><code>{{ $registro->codigo }}</code></td>
                        <td>
                            <span class="material-icons text-primary align-middle me-1" style="font-size:1.1rem;">person</span>
                            <span class="fw-semibold align-middle">{{ $nombreUsuario }}</span>
                            @if(!$registro->user)
                                <br><small class="text-muted">Usuario eliminado</small>
                            @endif
                        </td>
                        <td><span class="badge {{ $moduloConfig['class'] }} text-uppercase" style="letter-spacing:0.3px;">{{ $moduloConfig['label'] }}</span></td>
                        <td><span class="badge {{ $accionConfig['class'] }} text-uppercase" style="letter-spacing:0.3px;">{{ $accionConfig['label'] }}</span></td>
                        <td>{{ Str::limit($registro->detalle, 50) }}</td>
                        <td data-order="{{ $registro->created_at->timestamp }}">
                            <small class="text-muted d-flex align-items-center gap-1">
                                <i class="material-icons" style="font-size:1rem;">event</i>
                                {{ $registro->created_at->format('d/m/Y h:i A') }}
                            </small>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-ver-bitacora"
                                data-bitacora='@json($payload)'>
                                <i class="material-icons" style="font-size:1.1rem;">visibility</i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="material-icons" style="font-size:2rem;">inbox</i>
                            <div>No hay registros en la bitácora</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@include('dashboard.bitacora.modals.detalle')
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabla = $('#tablaBitacora').DataTable({
        language: { url: "{{ asset('datatables-i18n-es.json') }}" },
        order: [[5, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 6] }
        ],
        pageLength: 10
    });

    const escapeRegex = str => str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    $('#filtroModulo').on('change', function() {
        const val = $(this).val();
        tabla.column(2).search(val ? '^' + escapeRegex(val) + '$' : '', true, false).draw();
    });

    $('#filtroAccion').on('change', function() {
        const val = $(this).val();
        tabla.column(3).search(val ? '^' + escapeRegex(val) + '$' : '', true, false).draw();
    });

    $('#filtroUsuario').on('input', function() {
        tabla.column(1).search($(this).val()).draw();
    });

    $('#limpiarFiltros').on('click', function() {
        $('#filtroModulo').val('');
        $('#filtroAccion').val('');
        $('#filtroUsuario').val('');
        tabla.columns([1,2,3]).search('').draw();
    });

    const modalEl = document.getElementById('modalBitacora');
    const modal = new bootstrap.Modal(modalEl);
    const fmtJson = (obj) => obj ? JSON.stringify(obj, null, 2) : '-';

    $('#tablaBitacora').on('click', '.btn-ver-bitacora', function() {
        const data = $(this).data('bitacora');
        if (!data) return;

        $('#modalBitacoraCodigo').text(data.codigo || 'Detalle');
        $('#modalBitacoraUsuario').text(data.usuario || 'Usuario eliminado');
        $('#modalBitacoraModulo')
            .text(data.modulo || '-')
            .removeClass()
            .addClass('badge bg-primary');
        $('#modalBitacoraAccion')
            .text(data.accion || '-')
            .removeClass()
            .addClass('badge bg-success');

        const fecha = data.fecha ? new Date(data.fecha).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) : '-';
        $('#modalBitacoraFecha').text(fecha);
        $('#modalBitacoraDetalle').text(data.detalle || '-');
        $('#modalBitacoraAntes').text(fmtJson(data.datos_anteriores));
        $('#modalBitacoraDespues').text(fmtJson(data.datos_nuevos));

        modal.show();
    });
});
</script>
@endpush