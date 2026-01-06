@extends('layouts.material')

@section('title', 'Administración de Reportes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Administración de Reportes</h3>
                    <div class="card-tools">
                        <a href="{{ route('dashboard.reportes-admin.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Reporte
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="categoria" class="form-control">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>
                                            {{ $categoria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="activo" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                                    <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary">Filtrar</button>
                                <a href="{{ route('dashboard.reportes-admin.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tabla de reportes -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Template</th>
                                    <th>Categoría</th>
                                    <th>Requiere DB</th>
                                    <th>Estado</th>
                                    <th>Archivo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reportes as $reporte)
                                    <tr>
                                        <td>{{ $reporte->nombre }}</td>
                                        <td>{{ $reporte->template }}</td>
                                        <td>
                                            @if($reporte->categoria)
                                                <span class="badge badge-info">{{ $reporte->categoria }}</span>
                                            @else
                                                <span class="text-muted">Sin categoría</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reporte->requiere_db)
                                                <span class="badge badge-warning">Sí</span>
                                            @else
                                                <span class="badge badge-success">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reporte->activo)
                                                <span class="badge badge-success">Activo</span>
                                            @else
                                                <span class="badge badge-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($reporte->templateExists())
                                                <span class="badge badge-success">✓ Existe</span>
                                            @else
                                                <span class="badge badge-danger">✗ No existe</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('dashboard.reportes-admin.show', $reporte) }}" class="btn btn-info" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('dashboard.reportes-admin.edit', $reporte) }}" class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('dashboard.reportes-admin.toggle', $reporte) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn {{ $reporte->activo ? 'btn-secondary' : 'btn-success' }}" title="{{ $reporte->activo ? 'Desactivar' : 'Activar' }}">
                                                        <i class="fas {{ $reporte->activo ? 'fa-ban' : 'fa-check' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('dashboard.reportes-admin.destroy', $reporte) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('¿Estás seguro de eliminar este reporte?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No se encontraron reportes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    {{ $reportes->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection