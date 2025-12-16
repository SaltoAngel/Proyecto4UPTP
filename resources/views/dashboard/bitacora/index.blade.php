@extends('layouts.material')

@section('title', 'Bitácora')

@push('styles')
<style>
    .badge-CREATE { background-color: #43a047; }
    .badge-UPDATE { background-color: #ffa726; color: #000; }
    .badge-DELETE { background-color: #e53935; }
    .badge-LOGIN { background-color: #26c6da; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="material-icons me-2">history</i>
                    Bitácora de Cambios
                </h5>
            </div>

            <div class="card-body">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Módulo</label>
                        <input type="text" class="form-control" name="modulo" value="{{ request('modulo') }}" placeholder="Buscar módulo...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" value="{{ request('usuario') }}" placeholder="Buscar usuario...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" class="form-control" name="fecha_desde" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" class="form-control" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="material-icons me-1" style="font-size:1.1rem;">search</i>Filtrar
                        </button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
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
                            <tr>
                                <td><code>{{ $registro->codigo }}</code></td>
                                <td class="d-flex align-items-center gap-2">
                                    <i class="material-icons text-primary" style="font-size:1.1rem;">person</i>
                                    <span>{{ $registro->user->name ?? 'Usuario eliminado' }}</span>
                                </td>
                                <td><span class="badge bg-secondary">{{ $registro->modulo }}</span></td>
                                <td><span class="badge badge-{{ $registro->accion }}">{{ $registro->accion }}</span></td>
                                <td>{{ Str::limit($registro->detalle, 50) }}</td>
                                <td>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="material-icons" style="font-size:1rem;">event</i>
                                        {{ $registro->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.bitacora.show', $registro) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="material-icons" style="font-size:1.1rem;">visibility</i>
                                    </a>
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

                <div class="d-flex justify-content-center mt-3">
                    {{ $bitacora->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection