@extends('layouts.material')

@section('title', 'Bitácora - '.$bitacora->codigo)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="material-icons">description</i>
                    <div>
                        <div class="small text-white-50">Detalle del registro</div>
                        <div class="fw-semibold">{{ $bitacora->codigo }}</div>
                    </div>
                </div>
                <a href="{{ route('dashboard.bitacora.index') }}" class="btn btn-light btn-sm text-primary">
                    <i class="material-icons" style="font-size:1.1rem;">arrow_back</i> Volver
                </a>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="d-flex align-items-center gap-2 text-secondary">
                            <i class="material-icons" style="font-size:1.2rem;">info</i> Información General
                        </h6>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold">Usuario</td>
                                <td>{{ $bitacora->user->name ?? 'Usuario eliminado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Módulo</td>
                                <td><span class="badge bg-secondary">{{ $bitacora->modulo }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Acción</td>
                                <td><span class="badge bg-primary">{{ $bitacora->accion }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Fecha</td>
                                <td>{{ $bitacora->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">IP</td>
                                <td><code>{{ $bitacora->ip_address }}</code></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="d-flex align-items-center gap-2 text-secondary">
                            <i class="material-icons" style="font-size:1.2rem;">terminal</i> Información Técnica
                        </h6>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="fw-semibold">User Agent</td>
                                <td><small>{{ Str::limit($bitacora->user_agent, 80) }}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-12">
                        <h6 class="d-flex align-items-center gap-2 text-secondary">
                            <i class="material-icons" style="font-size:1.2rem;">list_alt</i> Detalle de la Acción
                        </h6>
                        <div class="alert alert-info mb-0">
                            {{ $bitacora->detalle }}
                        </div>
                    </div>
                </div>

                @if($bitacora->datos_anteriores || $bitacora->datos_nuevos)
                <hr>
                <div class="row g-3">
                    @if($bitacora->datos_anteriores)
                    <div class="col-md-6">
                        <h6 class="d-flex align-items-center gap-2 text-secondary">
                            <i class="material-icons" style="font-size:1.2rem;">history</i> Datos Anteriores
                        </h6>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($bitacora->datos_anteriores, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                    @endif

                    @if($bitacora->datos_nuevos)
                    <div class="col-md-6">
                        <h6 class="d-flex align-items-center gap-2 text-secondary">
                            <i class="material-icons" style="font-size:1.2rem;">add_circle</i> Datos Nuevos
                        </h6>
                        <pre class="bg-light p-3 rounded"><code>{{ json_encode($bitacora->datos_nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection