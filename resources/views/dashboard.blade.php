@extends('layouts.material')

@section('title', 'Dashboard')

@section('content')
<!-- Breadcrumbs -->
<div class="row mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active"><span>Dashboard</span></li>
            </ol>
        </nav>
    </div>
    <div class="col-12"><hr class="my-2"></div>
    <div class="col-12 mb-2">
        <h5 class="mb-0 fw-semibold">Resumen</h5>
        <small class="text-muted">Accesos rápidos a los módulos principales</small>
    </div>
    
</div>

<!-- Tarjetas de acceso rápido -->
<div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
    <div class="col">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 p-3 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="text-uppercase text-muted mb-1">Usuarios</h6>
                    <h4 class="fw-semibold mb-2">Gestión</h4>
                    <p class="text-muted mb-1">Administra usuarios del sistema</p>
                    <small class="text-muted">Total: {{ isset($usuarios) ? number_format($usuarios) : '—' }}</small>
                    <a href="#" class="btn btn-primary disabled" aria-disabled="true">Ver Usuarios</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 p-3 rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="text-uppercase text-muted mb-1">Proveedores</h6>
                    <h4 class="fw-semibold mb-2">Administración</h4>
                    <p class="text-muted mb-1">Gestiona proveedores y categorías</p>
                    <small class="text-muted">Total: {{ isset($proveedores) ? number_format($proveedores) : '—' }}</small>
                    <a href="{{ route('dashboard.proveedores.index') }}" class="btn btn-success">Ver Proveedores</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center">
                <div class="me-3 p-3 rounded-circle bg-info text-white d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="text-uppercase text-muted mb-1">Reportes</h6>
                    <h4 class="fw-semibold mb-2">Panel</h4>
                    <p class="text-muted mb-1">Indicadores y reportes del sistema</p>
                    <small class="text-muted">Personas: {{ isset($personas) ? number_format($personas) : '—' }}</small>
                    <a href="#" class="btn btn-info text-white disabled" aria-disabled="true">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secciones principales -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-history text-warning"></i>
                    <span class="fw-semibold">Bitácora de Cambios</span>
                </div>
                <a href="{{ route('dashboard.bitacora.index') }}" class="btn btn-sm btn-warning text-dark">
                    <i class="fas fa-list me-1"></i> Ver Bitácora
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Registro de todas las acciones realizadas en el sistema.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-users text-primary"></i>
                    <span class="fw-semibold">Personas</span>
                </div>
                <a href="{{ route('dashboard.personas.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list me-1"></i> Ver Personas
                </a>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Registro y gestión de personas.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Mapa (prueba) - Venezuela con ChartGeo -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-globe-americas text-info"></i>
                    <span class="fw-semibold">Mapa de Venezuela (prueba)</span>
                </div>
                <small class="text-muted">Chart.js + ChartGeo</small>
            </div>
            <div class="card-body">
                <canvas id="vzlaMap" style="width:100%;max-width:100%;height:360px"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Información del Usuario</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Nombre:</strong> {{ optional(Auth::user())->full_name ?? optional(Auth::user())->email ?? 'Usuario' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ optional(Auth::user())->email ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Rol:</strong> {{ Auth::user() ? (Auth::user()->getRoleNames()->first() ?? 'Usuario') : 'Usuario' }}</p>
                        <p class="mb-1"><strong>Estado:</strong> <span class="badge text-bg-success">Activo</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Render de mapa con ChartGeo: resalta Venezuela usando id 862 (ISO-3166-1 numérico)
document.addEventListener('DOMContentLoaded', async () => {
    const el = document.getElementById('vzlaMap');
    if (!el || !window.Chart || !window.ChartGeo) return;
    try {
        // Cargamos datos del mundo desde un CDN
        const worldResp = await fetch('https://unpkg.com/world-atlas@2/countries-110m.json');
        const world = await worldResp.json();
        const countries = window.ChartGeo.topojson.feature(world, world.objects.countries).features;

        // Valor 1 sólo para Venezuela (862), 0 para el resto, para resaltarlo
        const data = countries.map(f => ({ feature: f, value: f.id === 862 ? 1 : 0 }));

        const ctx = el.getContext('2d');
        new window.Chart(ctx, {
            type: 'choropleth',
            data: {
                labels: countries.map(d => d.properties && (d.properties.name || d.properties.NAME) || d.id),
                datasets: [{
                    label: 'Venezuela',
                    data,
                    backgroundColor: (ctx) => {
                        const v = ctx.raw;
                        return (v && v.feature && v.feature.id === 862) ? '#0d6efd' : '#e9ecef';
                    },
                    borderColor: '#adb5bd',
                    outline: { // borde del mundo
                        feature: window.ChartGeo.topojson.mesh(world, world.objects.countries)
                    }
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const v = ctx.raw;
                                const name = (v && v.feature && v.feature.properties && (v.feature.properties.name || v.feature.properties.NAME)) || v.feature.id;
                                return name === 'Venezuela' || v.feature.id === 862 ? 'Venezuela' : name;
                            }
                        }
                    }
                },
                scales: {
                    projection: {
                        type: 'projection',
                        axis: 'x',
                        projection: 'equalEarth'
                    }
                }
            }
        });
    } catch (e) {
        console.error('Error cargando mapa:', e);
    }
});
</script>
@endpush